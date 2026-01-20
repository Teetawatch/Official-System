<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypingMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_id',
        'player2_id',
        'winner_id',
        'text_content',
        'language',
        'status',
        'player1_progress',
        'player2_progress',
        'player1_wpm',
        'player2_wpm',
        'player1_accuracy',
        'player2_accuracy',
        'started_at',
        'completed_at',
        'tournament_id', // Added this line
        'round',         // Added this line
        'bracket_index', // Added this line
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function player1()
    {
        return $this->belongsTo(User::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function tournament() // Added this method
    {
        return $this->belongsTo(Tournament::class);
    }
}
