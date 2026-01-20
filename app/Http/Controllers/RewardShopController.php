<?php

namespace App\Http\Controllers;

use App\Models\RewardItem;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardShopController extends Controller
{
    /**
     * Display the reward shop
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');

        $itemsQuery = RewardItem::active()->orderBy('rarity', 'desc')->orderBy('price', 'asc');

        if ($type !== 'all') {
            $itemsQuery->ofType($type);
        }

        $items = $itemsQuery->get();

        // Get user's owned item IDs
        $ownedItemIds = UserReward::where('user_id', $user->id)->pluck('reward_item_id')->toArray();

        // Get statistics
        $stats = [
            'total_items' => RewardItem::active()->count(),
            'avatar_frames' => RewardItem::active()->ofType('avatar_frame')->count(),
            'themes' => RewardItem::active()->ofType('theme')->count(),
            'titles' => RewardItem::active()->ofType('title')->count(),
            'owned' => count($ownedItemIds),
        ];

        return view('typing.shop.index', compact('items', 'user', 'ownedItemIds', 'stats', 'type'));
    }

    /**
     * Purchase a reward item
     */
    public function purchase(Request $request, $id)
    {
        $user = Auth::user();
        $item = RewardItem::findOrFail($id);

        // Check if already owned
        $alreadyOwned = UserReward::where('user_id', $user->id)
            ->where('reward_item_id', $item->id)
            ->exists();

        if ($alreadyOwned) {
            return back()->with('error', 'คุณมีไอเทมนี้อยู่แล้ว!');
        }

        // Check if item is active and in stock
        if (!$item->is_active) {
            return back()->with('error', 'ไอเทมนี้ไม่พร้อมจำหน่าย');
        }

        if (!$item->isInStock()) {
            return back()->with('error', 'ไอเทมนี้หมดสต็อกแล้ว');
        }

        // Check if user has enough points/coins
        if ($user->points < $item->price) {
            return back()->with('error', 'คะแนนไม่เพียงพอ! คุณต้องการ ' . number_format($item->price) . ' คะแนน แต่มีเพียง ' . number_format($user->points) . ' คะแนน');
        }

        // Process purchase
        DB::transaction(function () use ($user, $item) {
            // Deduct points
            $user->points -= $item->price;
            $user->save();

            // Add to user rewards
            UserReward::create([
                'user_id' => $user->id,
                'reward_item_id' => $item->id,
                'is_equipped' => false,
                'purchased_at' => now(),
            ]);

            // Decrease stock if limited
            if ($item->stock !== null) {
                $item->stock -= 1;
                $item->save();
            }
        });

        return back()->with('success', '🎉 ซื้อ "' . $item->name . '" สำเร็จ! ไปติดตั้งได้ที่หน้ารางวัลของฉัน');
    }

    /**
     * Display user's rewards
     */
    public function myRewards()
    {
        $user = Auth::user();

        $rewards = UserReward::with('rewardItem')
            ->where('user_id', $user->id)
            ->orderBy('purchased_at', 'desc')
            ->get();

        // Group by type
        $grouped = [
            'avatar_frame' => $rewards->filter(fn($r) => $r->rewardItem->type === 'avatar_frame'),
            'theme' => $rewards->filter(fn($r) => $r->rewardItem->type === 'theme'),
            'title' => $rewards->filter(fn($r) => $r->rewardItem->type === 'title'),
        ];

        // Get currently equipped items
        $equipped = [
            'frame' => $user->equipped_frame,
            'theme' => $user->equipped_theme,
            'title' => $user->equipped_title,
        ];

        return view('typing.shop.my-rewards', compact('rewards', 'grouped', 'user', 'equipped'));
    }

    /**
     * Equip a reward item
     */
    public function equip(Request $request, $id)
    {
        $user = Auth::user();

        // Find the user reward
        $userReward = UserReward::with('rewardItem')
            ->where('user_id', $user->id)
            ->where('reward_item_id', $id)
            ->firstOrFail();

        $item = $userReward->rewardItem;

        // Unequip all items of same type first
        UserReward::where('user_id', $user->id)
            ->whereHas('rewardItem', fn($q) => $q->where('type', $item->type))
            ->update(['is_equipped' => false]);

        // Equip this item
        $userReward->is_equipped = true;
        $userReward->save();

        // Update user's equipped column
        switch ($item->type) {
            case 'avatar_frame':
                $user->equipped_frame = $item->id;
                break;
            case 'theme':
                $user->equipped_theme = $item->id;
                break;
            case 'title':
                $user->equipped_title = $item->id;
                break;
        }
        $user->save();

        return back()->with('success', '✅ ติดตั้ง "' . $item->name . '" เรียบร้อยแล้ว!');
    }

    /**
     * Unequip a reward item
     */
    public function unequip(Request $request, $id)
    {
        $user = Auth::user();

        // Find the user reward
        $userReward = UserReward::with('rewardItem')
            ->where('user_id', $user->id)
            ->where('reward_item_id', $id)
            ->firstOrFail();

        $item = $userReward->rewardItem;

        // Unequip
        $userReward->is_equipped = false;
        $userReward->save();

        // Update user's equipped column
        switch ($item->type) {
            case 'avatar_frame':
                $user->equipped_frame = null;
                break;
            case 'theme':
                $user->equipped_theme = null;
                break;
            case 'title':
                $user->equipped_title = null;
                break;
        }
        $user->save();

        return back()->with('success', '❌ ถอด "' . $item->name . '" เรียบร้อยแล้ว');
    }
}
