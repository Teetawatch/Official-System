<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypingAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level',
        'prerequisite_id',
        'required_score',
        'chapter',
        'title',
        'content',
        'master_file_path',
        'master_file_name',
        'check_formatting',
        'type',
        'submission_type',
        'difficulty_level',
        'max_score',
        'time_limit',
        'is_active',
        'due_date',
    ];

    protected $casts = [
        'level' => 'integer',
        'required_score' => 'float',
        'is_active' => 'boolean',
        'check_formatting' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany(TypingSubmission::class, 'assignment_id');
    }

    /**
     * Get the prerequisite assignment that must be completed before this one.
     */
    public function prerequisite()
    {
        return $this->belongsTo(TypingAssignment::class, 'prerequisite_id');
    }

    /**
     * Get the assignments that are unlocked by completing this one.
     */
    public function unlocks()
    {
        return $this->hasMany(TypingAssignment::class, 'prerequisite_id');
    }

    /**
     * Check if this assignment is unlocked for a specific user.
     */
    public function isUnlockedForUser($userId)
    {
        if (!$this->prerequisite_id) {
            return true;
        }

        // Check if user has a submission for the prerequisite with required score
        return $this->prerequisite->submissions()
            ->where('user_id', $userId)
            ->where('score', '>=', $this->required_score)
            ->exists();
    }

    /**
     * Boot method to cascade delete submissions when assignment is deleted.
     */
    protected static function boot()
    {
        parent::boot();

        // When assignment is deleted (including soft delete), also delete submissions
        static::deleting(function ($assignment) {
            $assignment->submissions()->delete();
        });
    }
}
