<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'max_participants',
        'champion_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
    ];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'tournament_participants')
            ->withPivot('seed', 'joined_at')
            ->withTimestamps(); // Actually manual timestamp in schema, so maybe not standard pivot timestamps
    }

    public function matches()
    {
        return $this->hasMany(TypingMatch::class);
    }

    public function champion()
    {
        return $this->belongsTo(User::class, 'champion_id');
    }
}
