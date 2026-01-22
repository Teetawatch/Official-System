<?php

namespace App\Http\Controllers;

use App\Models\TypingMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypingMatchController extends Controller
{
    public function index()
    {
        return view('typing.matches.index');
    }

    public function findMatch(Request $request)
    {
        $user = Auth::user();
        $language = $request->input('language', 'en'); // Default to English

        // Check if user is already in an active match
        $activeMatch = TypingMatch::where(function ($q) use ($user) {
            $q->where('player1_id', $user->id)
                ->orWhere('player2_id', $user->id);
        })
            ->whereIn('status', ['pending', 'ongoing'])
            ->first();

        if ($activeMatch) {
            return response()->json([
                'status' => 'found',
                'match_id' => $activeMatch->id
            ]);
        }

        // Look for a pending match to join (same language)
        $pendingMatch = TypingMatch::where('status', 'pending')
            ->where('player1_id', '!=', $user->id)
            ->where('language', $language)
            ->whereNull('player2_id')
            ->oldest()
            ->first();

        if ($pendingMatch) {
            $pendingMatch->update([
                'player2_id' => $user->id,
                'status' => 'ongoing',
                'started_at' => now()->addSeconds(5),
                'player1_ready' => true,
                'player2_ready' => true,
            ]);

            return response()->json([
                'status' => 'joined',
                'match_id' => $pendingMatch->id
            ]);
        }

        // Text samples for each language
        $texts = [
            'en' => [
                "The quick brown fox jumps over the lazy dog. Programming is the art of telling another human what one wants the computer to do.",
                "Success is not final, failure is not fatal: it is the courage to continue that counts. Believe you can and you're halfway there.",
                "In the end, it's not the years in your life that count. It's the life in your years. Life is what happens when you're busy making other plans.",
                "Technology is best when it brings people together. It has become appallingly obvious that our technology has exceeded our humanity.",
                "The only way to do great work is to love what you do. If you haven't found it yet, keep looking. Don't settle.",
                "Innovation distinguishes between a leader and a follower. Let's go invent tomorrow instead of worrying about what happened yesterday.",
                "Software is a great combination between artistry and engineering. Code is poetry.",
                "The function of good software is to make the complex appear to be simple.",
                "First, solve the problem. Then, write the code.",
                "Simplicity is the soul of efficiency. Good code is its own best documentation.",
                "The impetuous nature of the reactive approach to programming often leads to a chaotic codebase. Structured paradigms enforce a discipline that yields software of superior robustness and maintainability.",
                "Artificial intelligence is not a substitute for human intelligence; it is a tool to amplify human creativity and ingenuity. The true potential lies in the collaboration between man and machine.",
                "The fundamental problem of communication is that of reproducing at one point either exactly or approximately a message selected at another point. Frequently the messages have meaning.",
                "Debugging is twice as hard as writing the code in the first place. Therefore, if you write the code as cleverly as possible, you are, by definition, not smart enough to debug it.",
                "Any fool can write code that a computer can understand. Good programmers write code that humans can understand. Refactoring is the key to maintainability.",
            ],
            'th' => [
                "การศึกษาคือรากฐานสำคัญของการพัฒนาประเทศ เราต้องเรียนรู้และพัฒนาตนเองอยู่เสมอ เพื่อสร้างอนาคตที่ดีกว่า",
                "ความสำเร็จไม่ได้มาจากโชค แต่มาจากความพยายามและความมุ่งมั่น ถ้าคุณเชื่อว่าทำได้ คุณก็จะทำได้จริง",
                "ภาษาเป็นเครื่องมือสำคัญในการสื่อสาร การพิมพ์เร็วและแม่นยำจะช่วยให้เราทำงานได้อย่างมีประสิทธิภาพมากขึ้น",
                "ประเทศไทยมีวัฒนธรรมอันงดงาม เราควรภูมิใจในความเป็นไทย และช่วยกันอนุรักษ์สิ่งดีงามเหล่านี้ไว้ให้ลูกหลาน",
                "ความรู้ไม่มีวันหมด ยิ่งเรียนยิ่งรู้ว่ายังไม่รู้อะไรอีกมาก การเป็นผู้ใฝ่รู้จะทำให้เราเติบโตและก้าวหน้าในชีวิต",
                "ความพยายามอยู่ที่ไหน ความสำเร็จอยู่ที่นั่น คำคมนี้ยังคงใช้ได้จริงเสมอในการดำเนินชีวิต",
                "การเขียนโปรแกรมไม่ใช่เรื่องยาก ถ้าเรามีความตั้งใจและหมั่นฝึกฝนอยู่เสมอ",
                "เทคโนโลยีใหม่ๆ เกิดขึ้นทุกวัน เราต้องปรับตัวและเรียนรู้อยู่ตลอดเวลาเพื่อไม่ให้ตกยุค",
                "การตรงต่อเวลาเป็นคุณสมบัติที่ดีของคนทำงาน ไม่ว่าจะเป็นงานเล็กหรืองานใหญ่",
                "สุขภาพเป็นเรื่องสำคัญ การทำงานหนักแต่ไม่ดูแลสุขภาพอาจส่งผลเสียในระยะยาวได้",
                "การทำงานเป็นทีมช่วยให้งานสำเร็จได้เร็วขึ้น และได้ผลงานที่มีคุณภาพมากขึ้น",
                "ปรัชญาเศรษฐกิจพอเพียงเป็นแนวทางการดำเนินชีวิตและการปฏิบัติตนของประชาชนในทุกระดับ ตั้งแต่ระดับครอบครัว ระดับชุมชน จนถึงระดับรัฐ ทั้งในการพัฒนาและบริหารประเทศให้ดำเนินไปในทางสายกลาง",
                "การเปลี่ยนแปลงสภาพภูมิอากาศเป็นภัยคุกคามที่สำคัญที่สุดประการหนึ่งที่มนุษยชาติต้องเผชิญ อุณหภูมิเฉลี่ยของโลกที่เพิ่มสูงขึ้นอย่างต่อเนื่องส่งผลให้เกิดปรากฏการณ์ธรรมชาติที่รุนแรงและคาดเดาไม่ได้",
                "ในยุคดิจิทัลที่ข้อมูลข่าวสารหลั่งไหลเข้ามาอย่างมหาศาล ทักษะการคิดวิเคราะห์และแยกแยะความถูกต้องของข้อมูลจึงมีความสำคัญอย่างยิ่ง เราต้องรู้จักตั้งคำถาม ตรวจสอบแหล่งที่มา และไม่เชื่อสิ่งใดง่ายๆ",
                "การอนุรักษ์ทรัพยากรธรรมชาติและสิ่งแวดล้อมไม่ใช่หน้าที่ของใครคนใดคนหนึ่ง แต่เป็นความรับผิดชอบร่วมกันของทุกคนในสังคม การปรับเปลี่ยนพฤติกรรมเพียงเล็กน้อยในชีวิตประจำวันสามารถสร้างผลกระทบที่ยิ่งใหญ่ได้",
                "ความรับผิดชอบต่อสังคมขององค์กรธุรกิจ คือแนวคิดที่ว่าบริษัทควรดำเนินกิจการโดยคำนึงถึงผลกระทบต่อผู้มีส่วนได้ส่วนเสียทุกฝ่าย ไม่ว่าจะเป็นพนักงาน ลูกค้า ชุมชน และสิ่งแวดล้อม",
            ],
        ];

        $selectedTexts = $texts[$language] ?? $texts['en'];

        // Create a new match if none found
        $match = TypingMatch::create([
            'player1_id' => $user->id,
            'text_content' => $selectedTexts[array_rand($selectedTexts)],
            'language' => $language,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'created',
            'match_id' => $match->id
        ]);
    }

    public function show($id)
    {
        $match = TypingMatch::with(['player1', 'player2'])->findOrFail($id);
        $user = Auth::user();

        if ($match->player1_id !== $user->id && $match->player2_id !== $user->id) {
            return redirect()->route('typing.matches.index')->with('error', 'คุณไม่ได้อยู่ในห้องนี้');
        }

        return view('typing.matches.show', compact('match'));
    }

    public function status($id)
    {
        $match = TypingMatch::with(['player1', 'player2', 'winner'])->findOrFail($id);
        $user = Auth::user();

        // Check Ready Status (Auto-Check-In)
        if ($match->status === 'pending') {
            $isPlayer1 = $match->player1_id === $user->id;
            $isPlayer2 = $match->player2_id === $user->id;

            if (($isPlayer1 && !$match->player1_ready) || ($isPlayer2 && !$match->player2_ready)) {
                if ($isPlayer1) $match->player1_ready = true;
                if ($isPlayer2) $match->player2_ready = true;
                $match->save();
            }

            // Check if both ready to start
            $p2Ready = $match->player2_id ? $match->player2_ready : true;
            
            $shouldStart = ($match->player1_ready && $p2Ready);
            $overrideStartedAt = null;

            // Classroom Battle Synchronized Start
            if ($match->tournament_id && $match->tournament->type === 'class_battle') {
                if ($match->tournament->race_started_at) {
                    $shouldStart = true;
                    $overrideStartedAt = $match->tournament->race_started_at;
                } else {
                    $shouldStart = false; // Wait for teacher
                }
            }

            if ($shouldStart) {
                // Use transaction to ensure only one thread starts it
                DB::transaction(function () use ($match, $overrideStartedAt) {
                    $m = TypingMatch::lockForUpdate()->find($match->id);
                    if ($m->status === 'pending') {
                        $m->status = 'ongoing';
                        $m->started_at = $overrideStartedAt ?? now()->addSeconds(5);
                        $m->save();
                    }
                });
                $match->refresh();
            }
        }

        // Fetch other players progress if in a tournament
        $others = [];
        if ($match->tournament_id && $match->tournament->type === 'class_battle') {
            $others = TypingMatch::where('tournament_id', $match->tournament_id)
                ->where('id', '!=', $match->id)
                ->with('player1')
                ->get()
                ->map(function($m) {
                    return [
                        'name' => $m->player1->name,
                        'progress' => $m->player1_progress,
                        'wpm' => $m->player1_wpm,
                        'status' => $m->status,
                        'avatar' => $m->player1->avatar_url
                    ];
                });
        }

        return response()->json([
            'status' => $match->status,
            'started_at' => $match->started_at,
            'server_time' => now()->startOfSecond()->timestamp * 1000 + now()->milli, // Milliseconds
            'player1' => [
                'id' => $match->player1->id,
                'name' => $match->player1->name,
                'progress' => $match->player1_progress,
                'wpm' => $match->player1_wpm,
                'avatar' => $match->player1->avatar_url,
                'ready' => $match->player1_ready,
            ],
            'player2' => $match->player2 ? [
                'id' => $match->player2->id,
                'name' => $match->player2->name,
                'progress' => $match->player2_progress,
                'wpm' => $match->player2_wpm,
                'avatar' => $match->player2->avatar_url,
                'ready' => $match->player2_ready,
            ] : null,
            'winner' => $match->winner ? $match->winner->name : null,
            'winner_id' => $match->winner_id,
            'others' => $others,
            'tournament_race' => $match->tournament_id ? [
                'type' => $match->tournament->type,
                'race_started_at' => $match->tournament->race_started_at,
            ] : null
        ]);
    }

    public function updateProgress(Request $request, $id)
    {
        $match = TypingMatch::findOrFail($id);
        $user = Auth::user();

        $isPlayer1 = $match->player1_id === $user->id;

        $data = [
            $isPlayer1 ? 'player1_progress' : 'player2_progress' => $request->progress,
            $isPlayer1 ? 'player1_wpm' : 'player2_wpm' => $request->wpm,
            $isPlayer1 ? 'player1_accuracy' : 'player2_accuracy' => $request->accuracy,
        ];

        // Ensure status is ongoing if p2 just joined but status update lagged (rare race condition handling)
        if ($match->status === 'pending' && $match->player2_id && $match->player1_ready && $match->player2_ready) {
             // If we are here, status check didn't catch it yet, or race condition.
             // We generally prefer the status check to handle start.
             // But if we forced it here:
             // $data['status'] = 'ongoing';
             // $data['started_at'] = now()->addSeconds(5);
        }

        $match->update($data);

        return response()->json(['success' => true]);
    }

    public function finish(Request $request, $id)
    {
        $match = TypingMatch::findOrFail($id);
        $user = Auth::user();
        $isNewRecord = false;
        $finalWpm = $request->input('wpm', 0);

        // Use a transaction to prevent race conditions on winning
        DB::transaction(function () use ($match, $user, &$isNewRecord, $finalWpm) {
            // Reload to get latest state
            $match = TypingMatch::lockForUpdate()->find($match->id);

            if (!$match->winner_id) {
                $match->winner_id = $user->id;
                $match->status = 'completed';
                $match->completed_at = now();
                $match->save();

                // Check if this is a personal best WPM
                if ($finalWpm > ($user->best_match_wpm ?? 0)) {
                    $user->best_match_wpm = $finalWpm;
                    $user->save();
                    $isNewRecord = true;
                }

                // Check for Class Battle Mode
                if ($match->tournament_id && $match->tournament->type === 'class_battle') {
                    // Calculate Rank
                    $completedCount = TypingMatch::where('tournament_id', $match->tournament_id)
                        ->where('status', 'completed')
                        ->where('id', '!=', $match->id) // Exclude self just in case (though we locked row, status was likely pending)
                        ->count();
                    
                    $rank = $completedCount + 1;
                    
                    // Calculate Points from Tournament Config
                    $config = $match->tournament->scoring_config ?? [
                        'first_place' => 100,
                        'second_place' => 90,
                        'third_place' => 80,
                        'decrement' => 2,
                        'min_points' => 10
                    ];
                    
                    // Decode if it's a string (DB might return string for json column depending on driver/cast)
                    if (is_string($config)) $config = json_decode($config, true);

                    $points = 0;
                    if ($rank === 1) $points = intval($config['first_place'] ?? 100);
                    else if ($rank === 2) $points = intval($config['second_place'] ?? 90);
                    else if ($rank === 3) $points = intval($config['third_place'] ?? 80);
                    else {
                        $base = intval($config['third_place'] ?? 80);
                        $decr = intval($config['decrement'] ?? 2);
                        $min = intval($config['min_points'] ?? 10);
                        $points = max($min, $base - (($rank - 3) * $decr));
                    }
                    
                    $user->increment('points', $points);
                    $user->increment('match_wins', 1); // Maybe count as win? Or just participate? Let's count as win for now or maybe distinct stat.
                    
                    // Save rank to match for reference (optional, but good for display)
                    // We don't have a specific rank column on match, but we can infer it later from finished_at order 
                    // OR we can misuse 'bracket_index' or add a new column. 
                    // For now, let's just award points.
                } else {
                    // Standard 1v1 Points
                    $user->increment('points', 50); // Winner gets 50 points
                    $user->increment('match_wins', 1);
                    
                    // Loser gets 10 points for participation
                    $loserId = ($match->player1_id === $user->id) ? $match->player2_id : $match->player1_id;
                    if ($loserId) {
                        $loser = User::find($loserId);
                        $loser->increment('points', 10);
                        $loser->increment('match_losses', 1);
                    }
                }

                // Tournament Advancement Logic
                if ($match->tournament_id && $match->round) {
                    $nextRound = $match->round + 1;
                    $nextBracketIndex = floor($match->bracket_index / 2);

                    $nextMatch = TypingMatch::where('tournament_id', $match->tournament_id)
                        ->where('round', $nextRound)
                        ->where('bracket_index', $nextBracketIndex)
                        ->first();

                    if ($nextMatch) {
                        // Determine if winner goes to slot 1 or 2
                        // Even index -> Player 1 slot, Odd index -> Player 2 slot (relative to current round pair)
                        // Example: Index 0 & 1 -> Next Match Index 0.
                        // 0 is even -> P1. 1 is odd -> P2.
                        $isPlayer1Slot = ($match->bracket_index % 2 === 0);

                        $updateData = $isPlayer1Slot ? ['player1_id' => $user->id] : ['player2_id' => $user->id];
                        $nextMatch->update($updateData);
                    } else {
                        // No next match? Maybe it was the final?
                        // If round was Final (e.g. 4 for 16 players), then we notify update tournament
                        // We can check if it was indeed the last round available in DB or hardcoded logic.
                        // Let's assume if no next match found, and it's a tournament, we might be done.
                        // Or simply check if this was the "Final" round.

                        // Check if specific tournament has this as last round
                        $maxRound = TypingMatch::where('tournament_id', $match->tournament_id)->max('round');
                        if ($match->round == $maxRound) {
                            $match->tournament->update(['champion_id' => $user->id, 'status' => 'completed']);
                        }
                    }
                }
            }
        });

        return response()->json([
            'success' => true,
            'is_new_record' => $isNewRecord
        ]);
    }

    /**
     * Get 1v1 Match Rankings
     */
    public function ranking()
    {
        $user = Auth::user();

        // Get top players by wins
        $topPlayers = User::where('role', 'student')
            ->where('match_wins', '>', 0)
            ->orderByDesc('match_wins')
            ->orderByDesc('best_match_wpm')
            ->limit(100)
            ->get()
            ->map(function ($player, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $player->id,
                    'name' => $player->name,
                    'avatar' => $player->avatar_url,
                    'class_name' => $player->class_name,
                    'wins' => $player->match_wins ?? 0,
                    'losses' => $player->match_losses ?? 0,
                    'best_wpm' => $player->best_match_wpm ?? 0,
                    'win_rate' => ($player->match_wins + $player->match_losses) > 0
                        ? round(($player->match_wins / ($player->match_wins + $player->match_losses)) * 100)
                        : 0,
                    'equipped_frame' => $player->equipped_frame,
                    'equipped_title' => $player->equipped_title,
                ];
            });

        // Get current user's stats
        $myStats = [
            'wins' => $user->match_wins ?? 0,
            'losses' => $user->match_losses ?? 0,
            'best_wpm' => $user->best_match_wpm ?? 0,
            'rank' => $topPlayers->search(fn($p) => $p['id'] === $user->id) + 1 ?: null,
        ];

        // Get recent matches
        $recentMatches = TypingMatch::with(['player1', 'player2', 'winner'])
            ->where('status', 'completed')
            ->where(function ($q) use ($user) {
                $q->where('player1_id', $user->id)
                    ->orWhere('player2_id', $user->id);
            })
            ->orderByDesc('completed_at')
            ->limit(10)
            ->get()
            ->map(function ($match) use ($user) {
                $isPlayer1 = $match->player1_id === $user->id;
                $opponent = $isPlayer1 ? $match->player2 : $match->player1;
                $myWpm = $isPlayer1 ? $match->player1_wpm : $match->player2_wpm;
                $opponentWpm = $isPlayer1 ? $match->player2_wpm : $match->player1_wpm;

                return [
                    'id' => $match->id,
                    'opponent' => $opponent ? [
                        'name' => $opponent->name,
                        'avatar' => $opponent->avatar_url,
                    ] : null,
                    'won' => $match->winner_id === $user->id,
                    'my_wpm' => $myWpm ?? 0,
                    'opponent_wpm' => $opponentWpm ?? 0,
                    'date' => $match->completed_at?->diffForHumans(),
                ];
            });

        return view('typing.matches.ranking', compact('topPlayers', 'myStats', 'recentMatches'));
    }
    /**
     * Cancel or Surrender Match
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();

        // Find active match
        $match = TypingMatch::where(function ($q) use ($user) {
            $q->where('player1_id', $user->id)
                ->orWhere('player2_id', $user->id);
        })
            ->whereIn('status', ['pending', 'ongoing'])
            ->first();

        if (!$match) {
            return response()->json(['success' => true, 'message' => 'No active match found']);
        }

        // If waiting for opponent, just delete the match room
        if ($match->status === 'pending') {
            $match->delete();
            return response()->json(['success' => true, 'action' => 'deleted']);
        }

        // If game started, it counts as surrender
        if ($match->status === 'ongoing') {
            $winnerId = ($match->player1_id === $user->id) ? $match->player2_id : $match->player1_id;

            DB::transaction(function () use ($match, $user, $winnerId) {
                $match->update([
                    'status' => 'completed',
                    'winner_id' => $winnerId,
                    'completed_at' => now(),
                ]);

                if ($winnerId) {
                    $winner = User::find($winnerId);
                    $winner->increment('points', 50);
                    $winner->increment('match_wins', 1);
                }

                $user->increment('match_losses', 1);
            });

            return response()->json(['success' => true, 'action' => 'surrendered']);
        }

        return response()->json(['success' => false]);
    }
}
