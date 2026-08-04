<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Carbon\Carbon;

class CareTaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all events for user's plants
        $events = collect();
        if ($user) {
            $plants = $user->gardens()->with('plants')->get()->pluck('plants')->flatten();
            $plantIds = $plants->pluck('id');
            
            $events = Event::with(['plant', 'eventType', 'plant.plantTemplate'])
                            ->whereIn('plant_id', $plantIds)
                            // Get events for today or past due, plus events that are completed/skipped today
                            ->where(function($query) {
                                $query->whereDate('scheduled_date', '<=', Carbon::today())
                                      ->whereIn('status', ['PENDING', 'MISSED'])
                                      ->orWhereDate('updated_at', Carbon::today());
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

        return view('users.care-tasks', [
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'skippedTasks' => $skippedTasks,
            'highPriorityCount' => $highPriorityCount,
            'totalCompleted' => $totalCompleted,
            'totalTasks' => $totalTasks > 0 ? $totalTasks : 1, // Avoid division by zero
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

        return redirect()->back()->with('success', 'Task marked as completed.');
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
