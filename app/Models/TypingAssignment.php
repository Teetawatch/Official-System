<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypingAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
        'is_active' => 'boolean',
        'check_formatting' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function submissions()
    {
        return $this->hasMany(TypingSubmission::class, 'assignment_id');
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
