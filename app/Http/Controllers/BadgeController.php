<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $totalUsers = $totalUsers > 0 ? $totalUsers : 1;
        
        $sync = BadgeService::syncUserBadges($user);
        $badges = $sync['badges'];
        $userBadgeIds = $sync['userBadgeIds'];
        $unlockedCount = count($userBadgeIds);
        $totalBadgeCount = $badges->count();

        $sort = $request->input('sort', 'default');

        if ($sort === 'rarest') {
            $badges = $badges->sortBy('users_count')->values();
        } elseif ($sort === 'most_owned') {
            $badges = $badges->sortByDesc('users_count')->values();
        } elseif ($sort === 'unlocked') {
            $badges = $badges->sortByDesc(function($badge) use ($userBadgeIds) {
                return in_array($badge->id, $userBadgeIds) ? 1 : 0;
            })->values();
        } elseif ($sort === 'locked') {
            $badges = $badges->sortBy(function($badge) use ($userBadgeIds) {
                return in_array($badge->id, $userBadgeIds) ? 1 : 0;
            })->values();
        }

        return view('users.badges', compact('badges', 'userBadgeIds', 'totalUsers', 'totalBadgeCount', 'unlockedCount', 'sort'));
    }
}
