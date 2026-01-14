<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypingSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignment_id',
        'wpm',
        'accuracy',
        'time_taken',
        'score',
        'feedback',
        'keystrokes_data',
        'file_path',
        'file_name',
        'file_hash',
        'file_metadata',
    ];

    protected $casts = [
        'keystrokes_data' => 'array',
        'file_metadata' => 'array',
        'accuracy' => 'float',
        'score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(TypingAssignment::class, 'assignment_id');
    }
}
