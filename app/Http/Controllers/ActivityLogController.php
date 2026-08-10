<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Garden;

class ActivityLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $isFree = $user && $user->role === 'free';
        
        $gardens = Garden::where('user_id', $user->id)->get();
        $gardenIds = $gardens->pluck('id');
        
        $plants = \App\Models\Plant::whereIn('garden_id', $gardenIds)->get();
        $plantIds = $plants->pluck('id');

        $query = Event::with(['plant', 'eventType', 'plant.plantTemplate'])
                    ->whereIn('plant_id', $plantIds)
                    ->whereIn('status', ['COMPLETED', 'SKIPPED'])
                    ->orderBy('updated_at', 'desc')
                    ->orderBy('scheduled_date', 'desc');

        $role = $user->role ?? 'free';
        $totalActivities = Event::whereIn('plant_id', $plantIds)
                                ->whereIn('status', ['COMPLETED', 'SKIPPED'])
                                ->count();

        if ($role === 'free') {
            // Free users only see the last 3 activities
            $activities = $query->take(3)->get();
            $hiddenCount = max(0, $totalActivities - 3);
        } elseif ($role === 'pro') {
            // Pro users see the last 10 activities
            $activities = $query->take(10)->get();
            $hiddenCount = max(0, $totalActivities - 10);
        } else {
            // Premium/Admin see unlimited paginated activity log
            $activities = $query->paginate(20);
            $hiddenCount = 0;
        }

        return view('users.activity-log', [
            'activities' => $activities,
            'isFree' => $isFree,
            'hiddenCount' => $hiddenCount
        ]);
    }
}
