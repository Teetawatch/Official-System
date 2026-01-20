<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TypingMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::withCount('participants')
            ->orderByDesc('created_at')
            ->get();

        return view('typing.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        // Simple create for testing/admin
        // In real app, this would be a form
        $tournament = Tournament::create([
            'name' => 'Weekly Speed Cubing #' . rand(1, 999),
            'description' => 'A bracket tournament for the fastest typists!',
            'max_participants' => 16,
            'status' => 'open',
        ]);

        return redirect()->route('tournaments.index')->with('success', 'Tournament created!');
    }

    public function join($id)
    {
        $tournament = Tournament::findOrFail($id);
        $user = Auth::user();

        if ($tournament->status !== 'open') {
            return back()->with('error', 'Tournament is not open for registration.');
        }

        if ($tournament->participants()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'You already joined this tournament.');
        }

        if ($tournament->participants()->count() >= $tournament->max_participants) {
            return back()->with('error', 'Tournament is full.');
        }

        // Attach user
        $tournament->participants()->attach($user->id);

        // Auto-start if full?
        if ($tournament->participants()->count() >= $tournament->max_participants) {
            $this->startTournament($tournament);
        }

        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Joined successfully!');
    }

    public function show($id)
    {
        $tournament = Tournament::with(['matches.player1', 'matches.player2', 'champion'])->findOrFail($id);

        // Group matches by round for easy display in view
        $matchesByRound = $tournament->matches->groupBy('round');

        return view('typing.tournaments.show', compact('tournament', 'matchesByRound'));
    }

    private function startTournament(Tournament $tournament)
    {
        DB::transaction(function () use ($tournament) {
            $tournament->status = 'ongoing';
            $tournament->start_date = now();
            $tournament->save();

            $participants = $tournament->participants()->inRandomOrder()->get();

            // Assuming 16 playes for now
            // Create Round 1 Matches
            $matchCount = $participants->count() / 2;

            for ($i = 0; $i < $matchCount; $i++) {
                $p1 = $participants[$i * 2];
                $p2 = $participants[($i * 2) + 1];

                TypingMatch::create([
                    'tournament_id' => $tournament->id,
                    'player1_id' => $p1->id,
                    'player2_id' => $p2->id,
                    'round' => 1,
                    'bracket_index' => $i,
                    'status' => 'pending',
                    'language' => 'en', // Default or make configurable
                    'text_content' => $this->getRandomText(),
                ]);
            }

            // Create placeholders for subsequent rounds if we want to show empty brackets?
            // Or just render them dynamically in view.
            // Let's create empty matches for R2, R3, R4 so IDs exist and structure is clear.
            $rounds = [
                2 => 4, // QF
                3 => 2, // SF
                4 => 1  // Final
            ];

            foreach ($rounds as $round => $count) {
                for ($i = 0; $i < $count; $i++) {
                    TypingMatch::create([
                        'tournament_id' => $tournament->id,
                        'player1_id' => null, // Placeholder
                        'player2_id' => null,
                        'round' => $round,
                        'bracket_index' => $i,
                        'status' => 'pending',
                        'language' => 'en',
                        'text_content' => $this->getRandomText(),
                    ]);
                }
            }
        });
    }

    private function getRandomText()
    {
        // Simple provider for now
        $texts = [
            "The quick brown fox jumps over the lazy dog.",
            "To be or not to be, that is the question.",
            "All that glitters is not gold.",
            "A journey of a thousand miles begins with a single step."
        ];
        return $texts[array_rand($texts)];
    }
}
