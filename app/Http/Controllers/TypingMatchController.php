<?php

namespace App\Http\Controllers;

use App\Models\TypingMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypingMatchController extends Controller
{
    public function index()
    {
        return view('typing.matches.index');
    }

    public function findMatch(Request $request)
    {
        $user = Auth::user();
        $language = $request->input('language', 'en'); // Default to English

        // Check if user is already in an active match
        $activeMatch = TypingMatch::where(function($q) use ($user) {
            $q->where('player1_id', $user->id)
              ->orWhere('player2_id', $user->id);
        })
        ->whereIn('status', ['pending', 'ongoing'])
        ->first();

        if ($activeMatch) {
            return response()->json([
                'status' => 'found',
                'match_id' => $activeMatch->id
            ]);
        }

        // Look for a pending match to join (same language)
        $pendingMatch = TypingMatch::where('status', 'pending')
            ->where('player1_id', '!=', $user->id)
            ->where('language', $language)
            ->whereNull('player2_id')
            ->oldest()
            ->first();

        if ($pendingMatch) {
            $pendingMatch->update([
                'player2_id' => $user->id,
                'status' => 'ongoing',
                'started_at' => now(),
            ]);

            return response()->json([
                'status' => 'joined',
                'match_id' => $pendingMatch->id
            ]);
        }

        // Text samples for each language
        $texts = [
            'en' => [
                "The quick brown fox jumps over the lazy dog. Programming is the art of telling another human what one wants the computer to do.",
                "Success is not final, failure is not fatal: it is the courage to continue that counts. Believe you can and you're halfway there.",
                "In the end, it's not the years in your life that count. It's the life in your years. Life is what happens when you're busy making other plans.",
                "Technology is best when it brings people together. It has become appallingly obvious that our technology has exceeded our humanity.",
            ],
            'th' => [
                "การศึกษาคือรากฐานสำคัญของการพัฒนาประเทศ เราต้องเรียนรู้และพัฒนาตนเองอยู่เสมอ เพื่อสร้างอนาคตที่ดีกว่า",
                "ความสำเร็จไม่ได้มาจากโชค แต่มาจากความพยายามและความมุ่งมั่น ถ้าคุณเชื่อว่าทำได้ คุณก็จะทำได้จริง",
                "ภาษาเป็นเครื่องมือสำคัญในการสื่อสาร การพิมพ์เร็วและแม่นยำจะช่วยให้เราทำงานได้อย่างมีประสิทธิภาพมากขึ้น",
                "ประเทศไทยมีวัฒนธรรมอันงดงาม เราควรภูมิใจในความเป็นไทย และช่วยกันอนุรักษ์สิ่งดีงามเหล่านี้ไว้ให้ลูกหลาน",
                "ความรู้ไม่มีวันหมด ยิ่งเรียนยิ่งรู้ว่ายังไม่รู้อะไรอีกมาก การเป็นผู้ใฝ่รู้จะทำให้เราเติบโตและก้าวหน้าในชีวิต",
            ],
        ];

        $selectedTexts = $texts[$language] ?? $texts['en'];

        // Create a new match if none found
        $match = TypingMatch::create([
            'player1_id' => $user->id,
            'text_content' => $selectedTexts[array_rand($selectedTexts)],
            'language' => $language,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'created',
            'match_id' => $match->id
        ]);
    }

    public function show($id)
    {
        $match = TypingMatch::with(['player1', 'player2'])->findOrFail($id);
        $user = Auth::user();

        if ($match->player1_id !== $user->id && $match->player2_id !== $user->id) {
            return redirect()->route('typing.matches.index')->with('error', 'คุณไม่ได้อยู่ในห้องนี้');
        }

        return view('typing.matches.show', compact('match'));
    }

    public function status($id)
    {
        $match = TypingMatch::with(['player1', 'player2', 'winner'])->findOrFail($id);
        
        return response()->json([
            'status' => $match->status,
            'player1' => [
                'id' => $match->player1->id,
                'name' => $match->player1->name, 
                'progress' => $match->player1_progress,
                'wpm' => $match->player1_wpm,
                'avatar' => $match->player1->avatar_url,
            ],
            'player2' => $match->player2 ? [
                'id' => $match->player2->id,
                'name' => $match->player2->name,
                'progress' => $match->player2_progress,
                'wpm' => $match->player2_wpm,
                'avatar' => $match->player2->avatar_url,
            ] : null,
            'winner' => $match->winner ? $match->winner->name : null,
            'winner_id' => $match->winner_id,
        ]);
    }

    public function updateProgress(Request $request, $id)
    {
        $match = TypingMatch::findOrFail($id);
        $user = Auth::user();
        
        $isPlayer1 = $match->player1_id === $user->id;
        
        $data = [
            $isPlayer1 ? 'player1_progress' : 'player2_progress' => $request->progress,
            $isPlayer1 ? 'player1_wpm' : 'player2_wpm' => $request->wpm,
            $isPlayer1 ? 'player1_accuracy' : 'player2_accuracy' => $request->accuracy,
        ];

        // Ensure status is ongoing if p2 just joined but status update lagged (rare race condition handling)
        if ($match->status === 'pending' && $match->player2_id) {
            $data['status'] = 'ongoing';
            $data['started_at'] = now();
        }

        $match->update($data);

        return response()->json(['success' => true]);
    }

    public function finish(Request $request, $id)
    {
        $match = TypingMatch::findOrFail($id);
        $user = Auth::user();
        
        // Use a transaction to prevent race conditions on winning
        DB::transaction(function() use ($match, $user) {
            // Reload to get latest state
            $match = TypingMatch::lockForUpdate()->find($match->id);
            
            if (!$match->winner_id) {
                $match->winner_id = $user->id;
                $match->status = 'completed';
                $match->completed_at = now();
                $match->save();

                // Award points
                $user->increment('points', 50); // Winner gets 50 points
                
                // Loser gets 10 points for participation
                $loserId = ($match->player1_id === $user->id) ? $match->player2_id : $match->player1_id;
                if ($loserId) {
                    User::find($loserId)->increment('points', 10);
                }
            }
        });

        return response()->json(['success' => true]);
    }
}
