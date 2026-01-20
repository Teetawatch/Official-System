<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'price',
        'image',
        'data',
        'rarity',
        'is_active',
        'stock',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get users who own this reward
     */
    public function owners()
    {
        return $this->belongsToMany(User::class, 'user_rewards')
            ->withPivot('is_equipped', 'purchased_at')
            ->withTimestamps();
    }

    /**
     * Get rarity color classes
     */
    public function getRarityColorAttribute()
    {
        return match ($this->rarity) {
            'legendary' => 'from-yellow-400 via-amber-500 to-orange-600',
            'epic' => 'from-purple-500 via-violet-500 to-purple-600',
            'rare' => 'from-blue-400 via-cyan-500 to-blue-600',
            default => 'from-gray-400 via-gray-500 to-gray-600',
        };
    }

    /**
     * Get rarity badge color
     */
    public function getRarityBadgeAttribute()
    {
        return match ($this->rarity) {
            'legendary' => 'bg-gradient-to-r from-yellow-400 to-amber-500 text-white',
            'epic' => 'bg-gradient-to-r from-purple-500 to-violet-500 text-white',
            'rare' => 'bg-gradient-to-r from-blue-400 to-cyan-500 text-white',
            default => 'bg-gray-200 text-gray-700',
        };
    }

    /**
     * Get rarity name in Thai
     */
    public function getRarityNameAttribute()
    {
        return match ($this->rarity) {
            'legendary' => 'ตำนาน',
            'epic' => 'เอพิค',
            'rare' => 'หายาก',
            default => 'ธรรมดา',
        };
    }

    /**
     * Get type name in Thai
     */
    public function getTypeNameAttribute()
    {
        return match ($this->type) {
            'avatar_frame' => 'กรอบอวาตาร์',
            'theme' => 'ธีมโปรไฟล์',
            'title' => 'ตำแหน่งพิเศษ',
            default => 'อื่นๆ',
        };
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute()
    {
        return match ($this->type) {
            'avatar_frame' => 'fa-circle-user',
            'theme' => 'fa-palette',
            'title' => 'fa-crown',
            default => 'fa-gift',
        };
    }

    /**
     * Check if item is in stock
     */
    public function isInStock(): bool
    {
        if ($this->stock === null) {
            return true;
        }
        return $this->stock > 0;
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for items by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
