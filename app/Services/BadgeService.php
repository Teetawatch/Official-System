<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\TypingSubmission;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Check and award badges to a user after a submission.
     */
    public function checkAndAwardBadges(User $user, TypingSubmission $submission)
    {
        $newBadges = [];

        // 1. Accuracy Master (100% Accuracy)
        if ($submission->accuracy >= 100) {
            $badge = Badge::where('slug', 'accuracy-master')->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id);
                $newBadges[] = $badge;
            }
        }

        // 2. Speed Demon (WPM >= 60)
        if ($submission->wpm >= 60) {
            $badge = Badge::where('slug', 'speed-demon')->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id);
                $newBadges[] = $badge;
            }
        }

        // 3. Iron Finger (Cumulative typing time >= 1 hour)
        $totalTime = $user->typingSubmissions()->sum('time_taken');
        if ($totalTime >= 3600) { // 3600 seconds = 1 hour
            $badge = Badge::where('slug', 'iron-finger')->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id);
                $newBadges[] = $badge;
            }
        }

        // 4. Persistence (Submit 10 assignments)
        $submissionCount = $user->typingSubmissions()->count();
        if ($submissionCount >= 10) {
            $badge = Badge::where('slug', 'persistence-master')->first();
            if ($badge && !$user->badges()->where('badge_id', $badge->id)->exists()) {
                $user->badges()->attach($badge->id);
                $newBadges[] = $badge;
            }
        }

        return $newBadges;
    }
}
