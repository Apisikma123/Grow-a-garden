<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $totalUsers = $totalUsers > 0 ? $totalUsers : 1;
        
        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();
        $unlockedCount = count($userBadgeIds);

        $sort = $request->input('sort', 'default');
        
        // Base query with users count
        $query = Badge::withCount('users');

        $badges = $query->get();
        $totalBadgeCount = $badges->count();

        // Perform sorting in memory since we need complex logic for 'locked/unlocked'
        if ($sort === 'rarest') {
            $badges = $badges->sortBy('users_count')->values();
        } elseif ($sort === 'most_owned') {
            $badges = $badges->sortByDesc('users_count')->values();
        } elseif ($sort === 'unlocked') {
            // Unlocked first, then locked
            $badges = $badges->sortByDesc(function($badge) use ($userBadgeIds) {
                return in_array($badge->id, $userBadgeIds) ? 1 : 0;
            })->values();
        } elseif ($sort === 'locked') {
            // Locked first, then unlocked
            $badges = $badges->sortBy(function($badge) use ($userBadgeIds) {
                return in_array($badge->id, $userBadgeIds) ? 1 : 0;
            })->values();
        }

        return view('users.badges', compact('badges', 'userBadgeIds', 'totalUsers', 'totalBadgeCount', 'unlockedCount', 'sort'));
    }
}
