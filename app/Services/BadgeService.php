<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Event;
use App\Models\Plant;
use App\Models\User;
use Carbon\Carbon;

class BadgeService
{
    /**
     * Get aggregate statistics for a user's gardening activity.
     */
    public static function getUserStats(User $user): array
    {
        $userGardenIds = $user->gardens()->pluck('id');
        $userPlantIds = Plant::whereIn('garden_id', $userGardenIds)->pluck('id');

        $completedEvents = Event::whereIn('plant_id', $userPlantIds)
            ->where('status', 'COMPLETED')
            ->with('eventType')
            ->get();

        $skippedCount = Event::whereIn('plant_id', $userPlantIds)
            ->where('status', 'SKIPPED')
            ->count();

        $totalCompletedCount = $completedEvents->count();

        $wateringCount = $completedEvents->filter(function ($e) {
            if (!$e->eventType) return false;
            $code = strtolower($e->eventType->code ?? '');
            $label = strtolower($e->eventType->label ?? '');
            return str_contains($code, 'water') || str_contains($label, 'siram');
        })->count();

        $fertilizingCount = $completedEvents->filter(function ($e) {
            if (!$e->eventType) return false;
            $code = strtolower($e->eventType->code ?? '');
            $label = strtolower($e->eventType->label ?? '');
            return str_contains($code, 'fertiliz') || str_contains($label, 'pupuk');
        })->count();

        $pruningCount = $completedEvents->filter(function ($e) {
            if (!$e->eventType) return false;
            $code = strtolower($e->eventType->code ?? '');
            $label = strtolower($e->eventType->label ?? '');
            return str_contains($code, 'prun') || str_contains($label, 'pangkas') || str_contains($label, 'rempel');
        })->count();

        $pestCount = $completedEvents->filter(function ($e) {
            if (!$e->eventType) return false;
            $code = strtolower($e->eventType->code ?? '');
            $label = strtolower($e->eventType->label ?? '');
            return str_contains($code, 'pest') || str_contains($label, 'hama');
        })->count();

        $harvestCount = $completedEvents->filter(function ($e) {
            if (!$e->eventType) return false;
            $code = strtolower($e->eventType->code ?? '');
            $label = strtolower($e->eventType->label ?? '');
            return str_contains($code, 'harvest') || str_contains($label, 'panen');
        })->count();

        $gardensCount = $userGardenIds->count();
        $plantsCount = $userPlantIds->count();
        $role = strtolower($user->role ?? '');
        $isPro = in_array($role, ['pro', 'premium', 'admin']) ? 1 : 0;
        $isPremium = in_array($role, ['premium', 'admin']) ? 1 : 0;

        return [
            'total_tasks' => $totalCompletedCount,
            'skipped'     => $skippedCount,
            'watering'    => $wateringCount,
            'fertilizing' => $fertilizingCount,
            'pruning'     => $pruningCount,
            'pest'        => $pestCount,
            'harvest'     => $harvestCount,
            'gardens'     => $gardensCount,
            'plants'      => $plantsCount,
            'pro'         => $isPro,
            'premium'     => $isPremium,
        ];
    }

    /**
     * Calculate progress for a specific badge.
     */
    public static function calculateProgress(Badge $badge, array $userStats, bool $isEarned): array
    {
        $target = 1;
        // Strip periods in formatted numbers like 1.000 -> 1000
        $cleanDesc = str_replace('.', '', $badge->description ?? '');
        if (preg_match('/(\d+)/', $cleanDesc, $matches)) {
            $target = (int) $matches[1];
        }
        if ($target <= 0) $target = 1;

        $name = strtolower($badge->name ?? '');
        $desc = strtolower($badge->description ?? '');

        // --- Priority-ordered routing (most-specific keywords FIRST) ---

        // 1. Subscription Badges (Pro & Premium) — checked FIRST so 'panen' in Panen Raya isn't caught by harvest task rule
        if (str_contains($name, 'sang pro') || str_contains($desc, 'subur (pro)') || str_contains($desc, 'grow a garden pro')) {
            $current = $userStats['pro'];

        } elseif (str_contains($name, 'panen raya premium') || str_contains($name, 'pekebun panen raya') || str_contains($desc, 'panen raya (premium)')) {
            $current = $userStats['premium'];

        // 2. Watering
        } elseif (str_contains($name, 'siram') || str_contains($name, 'water') || str_contains($name, 'setetes') || str_contains($desc, 'penyiraman')) {
            $current = $userStats['watering'];

        // 3. Fertilizing
        } elseif (str_contains($name, 'pupuk') || str_contains($name, 'pemupukan') || str_contains($desc, 'pemupukan')) {
            $current = $userStats['fertilizing'];

        // 4. Pruning
        } elseif (str_contains($name, 'pangkas') || str_contains($name, 'pemangkasan') || str_contains($name, 'potongan') || str_contains($desc, 'pemangkasan')) {
            $current = $userStats['pruning'];

        // 5. Pest / Hama
        } elseif (str_contains($name, 'hama') || str_contains($name, 'pembasmi') || str_contains($desc, 'pembasmian') ||
                  (str_contains($desc, 'hama') && !str_contains($name, 'kebun'))) {
            $current = $userStats['pest'];

        // 6. Harvest / Panen
        } elseif (str_contains($name, 'panen') || str_contains($desc, 'panen')) {
            $current = $userStats['harvest'];

        // 7. Skip badges
        } elseif (str_contains($name, 'santai') || str_contains($name, 'rebahan') || str_contains($name, 'cuti') ||
                  str_contains($name, 'terlantar') || str_contains($name, 'mengamati') || str_contains($desc, 'lewati (skip)')) {
            $current = $userStats['skipped'];

        // 8. Plants (tanaman) — checked BEFORE gardens to prevent "ke kebun" in desc hijacking
        } elseif (str_contains($name, 'tanaman') || str_contains($desc, 'menambahkan') || str_contains($desc, 'menanam')) {
            $current = $userStats['plants'];

        // 9. Gardens (kebun) — after plants so "ke kebun" in plant descs doesn't match here
        } elseif (str_contains($name, 'kebun') || str_contains($name, 'pekebun') || str_contains($name, 'ekosistem') ||
                  (str_contains($desc, 'kebun') && !str_contains($desc, 'ke kebun'))) {
            $current = $userStats['gardens'];

        // 10. Langkah Perdana / General task completion (first task etc.)
        } elseif (str_contains($name, 'langkah') || str_contains($desc, 'menyelesaikan tugas')) {
            $current = $userStats['total_tasks'];

        // 11. Default: total tasks
        } else {
            $current = $userStats['total_tasks'];
        }

        if ($isEarned) {
            $current = max($current, $target);
        }

        $percentage = min(100, max(0, (int) round(($current / $target) * 100)));

        return [
            'target'     => $target,
            'current'    => $current,
            'percentage' => $percentage,
            'is_eligible'=> ($current >= $target),
        ];
    }

    /**
     * Sync user badges (auto-award any badge whose real progress requirement is met)
     * and attach progress properties to each badge. Badges once earned remain permanently unlocked.
     */
    public static function syncUserBadges(User $user): array
    {
        $userStats = static::getUserStats($user);
        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        $allBadges = Badge::withCount('users')->get();
        $newlyAwardedIds = [];

        foreach ($allBadges as $badge) {
            $isEarned = in_array($badge->id, $userBadgeIds);
            $progress = static::calculateProgress($badge, $userStats, $isEarned);

            if (!$isEarned && $progress['is_eligible']) {
                $user->badges()->syncWithoutDetaching([
                    $badge->id => ['awarded_at' => Carbon::now()]
                ]);
                $userBadgeIds[] = $badge->id;
                $newlyAwardedIds[] = $badge->id;
                $isEarned = true;
                $progress['percentage'] = 100;
                $progress['current'] = max($progress['current'], $progress['target']);
            }

            $badge->progress_target = $progress['target'];
            $badge->progress_current = $progress['current'];
            $badge->progress_pct = $progress['percentage'];
            $badge->is_earned = $isEarned;
        }

        return [
            'badges'          => $allBadges,
            'userBadgeIds'    => $userBadgeIds,
            'newlyAwardedIds' => $newlyAwardedIds,
        ];
    }

    /**
     * Get the unearned badge that is closest to completion.
     */
    public static function getClosestBadge(User $user): ?array
    {
        $sync = static::syncUserBadges($user);
        $badges = $sync['badges'];
        $userBadgeIds = $sync['userBadgeIds'];

        $closestBadge = null;
        $highestPct = -1;
        $closestTarget = 0;
        $closestCurrent = 0;

        foreach ($badges as $badge) {
            if (in_array($badge->id, $userBadgeIds)) {
                continue;
            }

            if ($badge->progress_pct > $highestPct) {
                $highestPct = $badge->progress_pct;
                $closestBadge = $badge;
                $closestTarget = $badge->progress_target;
                $closestCurrent = $badge->progress_current;
            }
        }

        if (!$closestBadge && $badges->whereNotIn('id', $userBadgeIds)->count() > 0) {
            $closestBadge = $badges->whereNotIn('id', $userBadgeIds)->first();
            $closestTarget = $closestBadge->progress_target ?? 1;
            $closestCurrent = $closestBadge->progress_current ?? 0;
        }

        if (!$closestBadge) {
            return null;
        }

        return [
            'badge'      => $closestBadge,
            'target'     => $closestTarget,
            'current'    => $closestCurrent,
            'percentage' => $highestPct > 0 ? $highestPct : 0,
        ];
    }
}
