<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_item_id',
        'is_equipped',
        'purchased_at',
    ];

    protected $casts = [
        'is_equipped' => 'boolean',
        'purchased_at' => 'datetime',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward item
     */
    public function rewardItem()
    {
        return $this->belongsTo(RewardItem::class);
    }
}
