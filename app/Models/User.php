<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'class_name',
        'avatar',
        'username',
        'is_registered',
        'points',
        'coins',
        'equipped_frame',
        'equipped_theme',
        'equipped_title',
        'equipped_name_color',
        'equipped_profile_bg',
    ];

    public function typingSubmissions()
    {
        return $this->hasMany(TypingSubmission::class);
    }

    /**
     * Get rewards owned by user
     */
    public function rewards()
    {
        return $this->belongsToMany(RewardItem::class, 'user_rewards')
            ->withPivot('is_equipped', 'purchased_at')
            ->withTimestamps();
    }

    /**
     * Get user rewards (pivot records)
     */
    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    /**
     * Get equipped frame (Relationship)
     */
    public function equippedFrame()
    {
        return $this->belongsTo(RewardItem::class, 'equipped_frame');
    }

    /**
     * Get equipped theme (Relationship)
     */
    public function equippedTheme()
    {
        return $this->belongsTo(RewardItem::class, 'equipped_theme');
    }

    /**
     * Get equipped title (Relationship)
     */
    public function equippedTitle()
    {
        return $this->belongsTo(RewardItem::class, 'equipped_title');
    }

    /**
     * Get equipped frame
     */
    public function getEquippedFrameItemAttribute()
    {
        if ($this->equipped_frame) {
            return RewardItem::find($this->equipped_frame);
        }
        return null;
    }

    /**
     * Get equipped theme
     */
    public function getEquippedThemeItemAttribute()
    {
        if ($this->equipped_theme) {
            return RewardItem::find($this->equipped_theme);
        }
        return null;
    }

    /**
     * Get equipped title
     */
    public function getEquippedTitleItemAttribute()
    {
        if ($this->equipped_title) {
            return RewardItem::find($this->equipped_title);
        }
        return null;
    }

    /**
     * Get equipped name color (Relationship)
     */
    public function equippedNameColor()
    {
        return $this->belongsTo(RewardItem::class, 'equipped_name_color');
    }

    /**
     * Get equipped profile background (Relationship)
     */
    public function equippedProfileBg()
    {
        return $this->belongsTo(RewardItem::class, 'equipped_profile_bg');
    }

    /**
     * Get equipped name color item
     */
    public function getEquippedNameColorItemAttribute()
    {
        if ($this->equipped_name_color) {
            return RewardItem::find($this->equipped_name_color);
        }
        return null;
    }

    /**
     * Get equipped profile background item
     */
    public function getEquippedProfileBgItemAttribute()
    {
        if ($this->equipped_profile_bg) {
            return RewardItem::find($this->equipped_profile_bg);
        }
        return null;
    }

    /**
     * Get avatar URL or fallback to UI Avatars
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(public_path($this->avatar))) {
            return asset($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2563eb&color=fff&size=120';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
