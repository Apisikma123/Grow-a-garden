<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Carbon\Carbon;

class CareTaskController extends Controller
{
    public function index(Request $request, \App\Services\WeatherService $weatherService, \App\Services\AutopilotService $autopilot)
    {
        $user = Auth::user();

        $isLocked = false;
        
        $events = collect();
        $weatherAdvice = null;

        if ($user && !$isLocked) {
            // Auto generate care tasks for user active plants if needed
            $autopilot->generateForUser($user);

            $gardens = $user->gardens()->with('plants')->get();
            $plants = $gardens->pluck('plants')->flatten();
            $plantIds = $plants->pluck('id');
            
            // Get location for weather
            $firstGarden = $gardens->first();
            $lat = $firstGarden->latitude ?? 3.58;
            $lng = $firstGarden->longitude ?? 98.67;

            // Fetch today weather & analyze agronomic rules
            $weather = $weatherService->getTodayWeather((float)$lat, (float)$lng);
            $agronomic = $weatherService->analyzeAgronomicConditions($weather);

            $events = Event::with(['plant', 'eventType', 'plant.plantTemplate'])
                            ->whereIn('plant_id', $plantIds)
                            ->where(function($query) {
                                $query->where(function($q) {
                                    $q->whereDate('scheduled_date', Carbon::today())
                                      ->whereIn('status', ['PENDING', 'MISSED']);
                                })->orWhere(function($q) {
                                    $q->whereDate('updated_at', Carbon::today())
                                      ->whereIn('status', ['COMPLETED', 'SKIPPED']);
                                });
                            })
                            ->orderBy('priority', 'asc')
                            ->orderBy('scheduled_date', 'asc')
                            ->get();

            // Apply Agronomic Weather-Task Synchronization Logic
            foreach ($events as $event) {
                $code = strtolower($event->eventType->code ?? '');

                if ($event->status === 'PENDING') {
                    if (str_contains($code, 'water')) {
                        $event->weather_tag = $agronomic['watering']['badge'];
                        $event->weather_badge_bg = $agronomic['watering']['badge_bg'];
                        $event->weather_reason = $agronomic['watering']['time_window'];

                        if ($agronomic['status'] === 'HEAT') {
                            $event->priority = 'HIGH';
                        }
                    } elseif (str_contains($code, 'fertiliz')) {
                        $event->weather_tag = $agronomic['fertilization']['badge'];
                        $event->weather_badge_bg = $agronomic['fertilization']['badge_bg'];
                        $event->weather_reason = $agronomic['fertilization']['advice'];
                    } elseif (str_contains($code, 'pest')) {
                        $event->weather_tag = $agronomic['pest_disease']['badge'];
                        $event->weather_badge_bg = $agronomic['pest_disease']['badge_bg'];
                        $event->weather_reason = $agronomic['pest_disease']['advice'];
                    }
                }
            }

            // Build Comprehensive Agronomic Weather Advice Banner
            $weatherAdvice = [
                'title' => $agronomic['watering']['title'] . ' • ' . $agronomic['summary'],
                'desc' => $agronomic['watering']['advice'] . ' ' . $agronomic['fertilization']['advice'],
                'icon' => ($agronomic['status'] === 'RAIN') ? 'rainy' : (($agronomic['status'] === 'HEAT') ? 'wb_sunny' : 'eco'),
                'badge' => $agronomic['watering']['badge'],
                'time_window' => $agronomic['watering']['time_window'],
                'agronomic' => $agronomic
            ];
        }

        $pendingTasks = $events->whereIn('status', ['PENDING', 'MISSED']);
        $completedTasks = $events->where('status', 'COMPLETED');
        $skippedTasks = $events->where('status', 'SKIPPED');

        $highPriorityCount = $pendingTasks->where('priority', 'HIGH')->count();
        $totalCompleted = $completedTasks->count();
        $totalTasks = $events->count();

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
            'totalTasks' => $totalTasks > 0 ? $totalTasks : 1,
            'isLocked' => $isLocked,
            'closestBadge' => $closestBadge,
            'closestTarget' => $closestTarget,
            'closestCurrent' => $closestCurrent,
            'weatherAdvice' => $weatherAdvice,
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
