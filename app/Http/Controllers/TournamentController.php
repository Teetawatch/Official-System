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
        return view('typing.tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:bracket,class_battle',
            'max_participants' => 'required|integer|min:2',
            'custom_text' => 'nullable|string|min:10',
            'time_limit' => 'nullable|integer|min:30|max:1800',
        ]);

        $type = $request->input('type');
        $name = $request->input('name');
        $description = $request->input('description');
        $maxParticipants = $request->input('max_participants');
        $customText = $request->input('custom_text');
        if ($customText) {
            $customText = str_replace(["\r\n", "\r"], "\n", trim($customText));
        }
        $timeLimit = $request->input('time_limit');

        $scoringConfig = null;
        if ($type === 'class_battle') {
            // Default Scoring Configuration
            $scoringConfig = [
                'first_place' => 100,
                'second_place' => 90,
                'third_place' => 80,
                'decrement' => 2,
                'min_points' => 10
            ];
            
            // Allow override from request
            if ($request->has('scoring_config')) {
                $scoringConfig = $request->input('scoring_config');
            }
        }

        $tournament = Tournament::create([
            'name' => $name,
            'description' => $description,
            'max_participants' => $maxParticipants,
            'status' => 'open',
            'type' => $type,
            'scoring_config' => $scoringConfig,
            'custom_text' => $customText,
            'time_limit' => $timeLimit,
        ]);

        return redirect()->route('typing.tournaments.show', $tournament->id)->with('success', 'Tournament created!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        
        // Delete related matches
        $tournament->matches()->delete(); // Model might have cascading but good to be sure
        $tournament->participants()->detach();
        $tournament->delete();

        return redirect()->route('typing.tournaments.index')->with('success', 'Tournament deleted successfully.');
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
        } else if ($tournament->type === 'class_battle' && $this->isAdmin($user)) {
             // Maybe manual start for class battle?
             // For now let's keep it auto-start on full or add a "Start" button in UI
        }

        return redirect()->route('typing.tournaments.show', $tournament->id)->with('success', 'Joined successfully!');
    }
    
    // Manual Start for Admins
    public function start($id)
    {
        $tournament = Tournament::findOrFail($id);
        if ($tournament->status !== 'open') return back();
        
        // Only admin/teacher check (assuming middleware handles or check here)
         if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'teacher') {
             return back()->with('error', 'Unauthorized');
         }

        $this->startTournament($tournament);
        return back()->with('success', 'Tournament started!');
    }

    public function show($id)
    {
        $tournament = Tournament::with(['matches.player1', 'matches.player2', 'champion'])->findOrFail($id);

        // Group matches by round for easy display in view
        $matchesByRound = $tournament->matches->groupBy('round');

        $nonParticipants = [];
        if ($tournament->type === 'class_battle') {
            $participantIds = $tournament->participants->pluck('id');
            $nonParticipants = User::where('role', 'student')
                                   ->whereNotIn('id', $participantIds)
                                   ->orderBy('name')
                                   ->get();
        }

        $isAdmin = $this->isAdmin(Auth::user());

        return view('typing.tournaments.show', compact('tournament', 'matchesByRound', 'nonParticipants', 'isAdmin'));
    }

    public function startRace($id)
    {
        $tournament = Tournament::findOrFail($id);
        
        if ($tournament->type !== 'class_battle' || $tournament->status !== 'ongoing') {
            return back()->with('error', 'Invalid tournament type or status.');
        }

        if ($tournament->race_started_at) {
            return back()->with('info', 'Race already started.');
        }

        // Set race start to 10 seconds from now
        $tournament->update([
            'race_started_at' => now()->addSeconds(10)
        ]);

        return back()->with('success', 'Global Race Countdown started!');
    }

    private function startTournament(Tournament $tournament)
    {
        DB::transaction(function () use ($tournament) {
            $tournament->status = 'ongoing';
            $tournament->start_date = now();
            $tournament->save();

            // Removed early return for class_battle as it's handled below

            $participants = $tournament->participants()->inRandomOrder()->get();
            
            // Determine text content: Custom or Random
            $textContent = $tournament->custom_text;
            if (empty($textContent)) {
                $textContent = $this->getRandomText();
            }

            // Create Round 1 Matches
            if ($tournament->type === 'class_battle') {
                foreach ($participants as $p) {
                    TypingMatch::create([
                        'tournament_id' => $tournament->id,
                        'player1_id' => $p->id,
                        'player2_id' => null, // Solo / Class Mode
                        'round' => 1,
                        'bracket_index' => 0,
                        'status' => 'pending', 
                        'language' => 'th', // Default to 'th' or detect
                        'text_content' => $textContent,
                        'time_limit' => $tournament->time_limit,
                    ]);
                }
                return;
            }

            // Bracket Logic
            $matchCount = intval(ceil($participants->count() / 2));

            for ($i = 0; $i < $matchCount; $i++) {
                $p1 = $participants[$i * 2] ?? null;
                $p2 = $participants[($i * 2) + 1] ?? null;

                if ($p1 && $p2) {
                    TypingMatch::create([
                        'tournament_id' => $tournament->id,
                        'player1_id' => $p1->id,
                        'player2_id' => $p2->id,
                        'round' => 1,
                        'bracket_index' => $i,
                        'status' => 'pending',
                        'language' => 'th', 
                        'text_content' => $textContent,
                        'time_limit' => $tournament->time_limit,
                    ]);
                } else if ($p1) {
                    // Odd number of players, bye round or auto-win?
                    // For now, let's just creating a pending match awaiting opponent or manual handling
                    // Or simpler: create match with null ID and handle in view
                     TypingMatch::create([
                        'tournament_id' => $tournament->id,
                        'player1_id' => $p1->id,
                        'player2_id' => null,
                        'round' => 1,
                        'bracket_index' => $i,
                        'status' => 'pending',
                        'language' => 'th',
                        'text_content' => $textContent,
                        'time_limit' => $tournament->time_limit,
                    ]);
                }
            }

            // Create placeholders for subsequent rounds
            $rounds = [
                2 => 4, // QF
                3 => 2, // SF
                4 => 1  // Final
            ];

            foreach ($rounds as $round => $count) {
                for ($i = 0; $i < $count; $i++) {
                    TypingMatch::create([
                        'tournament_id' => $tournament->id,
                        'player1_id' => null, 
                        'player2_id' => null,
                        'round' => $round,
                        'bracket_index' => $i,
                        'status' => 'pending',
                        'language' => 'th',
                        'text_content' => $textContent, // Usually upper rounds use different text? 
                                                        // If custom text is set, do we reuse it? 
                                                        // User asked to "set text", implying for the competition.
                                                        // Let's use it for all rounds for consistency if set, 
                                                        // OR we should probably generate new random text for upper rounds if NO custom text is set.
                        'time_limit' => $tournament->time_limit,
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
    private function isAdmin($user) {
        return in_array($user->role, ['admin', 'teacher']);
    }
}
