<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Carbon\Carbon;

class CareTaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $isLocked = false;
        
        $events = collect();
        if ($user && !$isLocked) {
            $plants = $user->gardens()->with('plants')->get()->pluck('plants')->flatten();
            $plantIds = $plants->pluck('id');
            
            $events = Event::with(['plant', 'eventType', 'plant.plantTemplate'])
                            ->whereIn('plant_id', $plantIds)
                            // Hanya ambil event yang dijadwalkan hari ini (bukan semua yang <= hari ini)
                            ->where(function($query) {
                                $query->where(function($q) {
                                    $q->whereDate('scheduled_date', Carbon::today())
                                      ->whereIn('status', ['PENDING', 'MISSED']);
                                })->orWhere(function($q) {
                                    $q->whereDate('updated_at', Carbon::today())
                                      ->whereIn('status', ['COMPLETED', 'SKIPPED']);
                                });
                            })
                            ->orderBy('scheduled_date', 'asc')
                            ->orderBy('priority', 'asc')
                            ->get();
        }

        $pendingTasks = $events->whereIn('status', ['PENDING', 'MISSED']);
        $completedTasks = $events->where('status', 'COMPLETED');
        $skippedTasks = $events->where('status', 'SKIPPED');

        $highPriorityCount = $pendingTasks->where('priority', 'HIGH')->count();
        $totalCompleted = $completedTasks->count();
        $totalTasks = $events->count(); // only tasks queried (relevant to today)

        if ($request->has('priority') && in_array($request->priority, ['HIGH', 'MEDIUM', 'LOW'])) {
            $pendingTasks = $pendingTasks->where('priority', $request->priority);
            $completedTasks = $completedTasks->where('priority', $request->priority);
            $skippedTasks = $skippedTasks->where('priority', $request->priority);
        }

        // Find closest badge using BadgeService
        $closestBadge = null;
        $closestTarget = 0;
        $closestCurrent = 0;
        if ($user) {
            $closestData = \App\Services\BadgeService::getClosestBadge($user);
            if ($closestData) {
                $closestBadge = $closestData['badge'];
                $closestTarget = $closestData['target'];
                $closestCurrent = $closestData['current'];
            }
        }

        return view('users.care-tasks', [
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'skippedTasks' => $skippedTasks,
            'highPriorityCount' => $highPriorityCount,
            'totalCompleted' => $totalCompleted,
            'totalTasks' => $totalTasks > 0 ? $totalTasks : 1, // Avoid division by zero
            'isLocked' => $isLocked,
            'closestBadge' => $closestBadge,
            'closestTarget' => $closestTarget,
            'closestCurrent' => $closestCurrent,
        ]);
    }

    public function complete(Event $event)
    {
        // Simple authorization check
        if ($event->plant->garden->user_id !== Auth::id()) {
            abort(403);
        }

        $event->update([
            'status' => 'COMPLETED',
            'completed_at' => Carbon::now(),
        ]);

        $user = Auth::user();
        $sync = \App\Services\BadgeService::syncUserBadges($user);

        $newBadge = null;
        if (!empty($sync['newlyAwardedIds'])) {
            $firstAwardedId = reset($sync['newlyAwardedIds']);
            $newBadge = \App\Models\Badge::find($firstAwardedId);
        }

        if ($newBadge) {
            return redirect()->back()->with('new_badge', [
                'name' => $newBadge->name,
                'description' => $newBadge->description,
                'icon_url' => $newBadge->icon_url
            ])->with('success', 'Tugas berhasil diselesaikan! Selamat atas pencapaian baru Anda!');
        }

        return redirect()->back()->with('success', 'Tugas berhasil diselesaikan.');
    }

    public function skip(Event $event)
    {
        // Simple authorization check
        if ($event->plant->garden->user_id !== Auth::id()) {
            abort(403);
        }

        $event->update([
            'status' => 'SKIPPED',
        ]);

        return redirect()->back()->with('info', 'Task skipped.');
    }
}
