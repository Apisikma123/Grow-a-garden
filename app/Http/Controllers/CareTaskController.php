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

        $isLocked = $user && !in_array($user->role, ['pro', 'premium', 'admin']);
        
        $events = collect();
        $weatherAdvice = null;

        if ($user) {
            // Auto generate care tasks for user active plants if needed
            $autopilot->generateForUser($user);

            $gardens = $user->gardens()->with('plants.plantTemplate')->get();
            $plants = $gardens->pluck('plants')->flatten();
            $plantIds = $plants->pluck('id');
            
            $rawEvents = Event::with(['plant', 'eventType', 'plant.plantTemplate'])
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

            // Intelligent Deduplication: Filter out redundant tasks (e.g. multiple fertilizing/pest checks in close interval)
            $filteredEvents = collect();
            $seenPlantTasks = [];

            foreach ($rawEvents as $event) {
                $code = $event->eventType->code ?? '';
                $plantId = $event->plant_id;
                $key = "{$plantId}_{$code}";

                if (isset($seenPlantTasks[$key])) {
                    // Duplicate for today, skip
                    continue;
                }

                // Fertilization anti-spam: Check if fertilized in past 6 days
                if (str_contains(strtolower($code), 'fertiliz') && $event->status === 'PENDING') {
                    $recentDone = Event::where('plant_id', $plantId)
                        ->where('id', '!=', $event->id)
                        ->whereHas('eventType', fn($q) => $q->where('code', 'like', '%fertiliz%'))
                        ->where('scheduled_date', '>=', Carbon::today()->subDays(6)->toDateString())
                        ->where('scheduled_date', '<', Carbon::today()->toDateString())
                        ->exists();

                    if ($recentDone) {
                        continue; // Skip redundant fertilizing task
                    }
                }

                // Pest inspection anti-spam: Check if pest checked in past 5 days
                if (str_contains(strtolower($code), 'pest') && $event->status === 'PENDING') {
                    $recentPest = Event::where('plant_id', $plantId)
                        ->where('id', '!=', $event->id)
                        ->whereHas('eventType', fn($q) => $q->where('code', 'like', '%pest%'))
                        ->where('scheduled_date', '>=', Carbon::today()->subDays(5)->toDateString())
                        ->where('scheduled_date', '<', Carbon::today()->toDateString())
                        ->exists();

                    if ($recentPest) {
                        continue; // Skip redundant pest inspection task
                    }
                }

                $seenPlantTasks[$key] = true;
                $filteredEvents->push($event);
            }

            $events = $filteredEvents;

            if (!$isLocked) {
                // Get location for weather
                $firstGarden = $gardens->first();
                $lat = $firstGarden->latitude ?? 3.58;
                $lng = $firstGarden->longitude ?? 98.67;

                // Fetch today weather & analyze agronomic rules
                $weather = $weatherService->getTodayWeather((float)$lat, (float)$lng);
                $agronomic = $weatherService->analyzeAgronomicConditions($weather);

                $windSpeed = $weather['wind_speed'] ?? 0;
                $temp = $weather['temperature'] ?? 29;
                $humidity = $weather['humidity'] ?? 70;
                $hadStorm = $weather['had_recent_storm'] ?? false;
                $pastPrecip = $weather['past_precipitation_24h'] ?? 0;

                // Dynamic Weather Event Tasks Injection based on real-time weather & plant HST
                foreach ($plants as $plant) {
                    $hst = max(0, (int) Carbon::parse($plant->planted_date)->diffInDays(Carbon::today()));
                    $templateName = $plant->plantTemplate->name_id ?? 'Tanaman';

                    // 1. High Wind / Storm Protection Quest
                    if ($windSpeed >= 15 || in_array($weather['weather_code'] ?? 0, [95, 96, 99]) || ($weather['upcoming_has_storm'] ?? false)) {
                        $actionDesc = ($windSpeed >= 15)
                            ? "Pindahkan pot ke tempat terlindung agar batang muda tidak patah diterpa angin kencang ({$windSpeed} km/j)."
                            : "Amankan pot bibit muda ke area terlindung sebelum potensi badai petir tiba agar batang tidak rusak.";

                        if ($hst <= 14) {
                            // Young seedling protection
                            $protectType = \App\Models\EventType::firstOrCreate(
                                ['code' => 'WEATHER_PROTECTION'],
                                ['label' => 'Proteksi Bibit Cuaca Ekstrem', 'category' => 'MAINTENANCE', 'default_priority' => 'HIGH']
                            );
                            
                            $existingTask = $events->first(fn($e) => $e->plant_id === $plant->id && $e->event_type_id === $protectType->id);
                            if (!$existingTask) {
                                $dynEvent = Event::firstOrCreate(
                                    [
                                        'plant_id' => $plant->id,
                                        'event_type_id' => $protectType->id,
                                        'scheduled_date' => Carbon::today()->toDateString(),
                                    ],
                                    [
                                        'status' => 'PENDING',
                                        'priority' => 'HIGH',
                                        'message' => "{$templateName}: Proteksi Bibit Cuaca Ekstrem — {$actionDesc}",
                                    ]
                                );
                                $dynEvent->load(['plant.plantTemplate', 'eventType', 'plant.garden']);
                                $events->prepend($dynEvent);
                            }
                        } else {
                            // Taller plant staking
                            $stakeType = \App\Models\EventType::firstOrCreate(
                                ['code' => 'STAKING'],
                                ['label' => 'Pemasangan & Penguatan Ajir', 'category' => 'MAINTENANCE', 'default_priority' => 'HIGH']
                            );
                            $existingTask = $events->first(fn($e) => $e->plant_id === $plant->id && $e->event_type_id === $stakeType->id);
                            if (!$existingTask) {
                                $dynEvent = Event::firstOrCreate(
                                    [
                                        'plant_id' => $plant->id,
                                        'event_type_id' => $stakeType->id,
                                        'scheduled_date' => Carbon::today()->toDateString(),
                                    ],
                                    [
                                        'status' => 'PENDING',
                                        'priority' => 'HIGH',
                                        'message' => "{$templateName}: Penguatan Ajir Penyangga — Kencangkan ikatan ajir agar tanaman kokoh menghadapi potensi angin kencang/badai.",
                                    ]
                                );
                                $dynEvent->load(['plant.plantTemplate', 'eventType', 'plant.garden']);
                                $events->prepend($dynEvent);
                            }
                        }
                    }

                    // 2. Post Heavy Rain / Storm Drainage Check Quest
                    if ($hadStorm || $pastPrecip >= 5.0 || ($agronomic['irrigation_decision'] ?? '') === 'SKIP') {
                        $drainType = \App\Models\EventType::firstOrCreate(
                            ['code' => 'DRAINAGE_CHECK'],
                            ['label' => 'Pemeriksaan Drainase Pot', 'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM']
                        );
                        $existingDrain = $events->first(fn($e) => $e->plant_id === $plant->id && $e->event_type_id === $drainType->id);
                        if (!$existingDrain) {
                            $dynEvent = Event::firstOrCreate(
                                [
                                    'plant_id' => $plant->id,
                                    'event_type_id' => $drainType->id,
                                    'scheduled_date' => Carbon::today()->toDateString(),
                                ],
                                [
                                    'status' => 'PENDING',
                                    'priority' => 'MEDIUM',
                                    'message' => "{$templateName}: Pemeriksaan Drainase Pot — Pastikan lubang bawah pot lancar dan buang air yang menggenang di alas pot.",
                                ]
                            );
                            $dynEvent->load(['plant.plantTemplate', 'eventType', 'plant.garden']);
                            $events->push($dynEvent);
                        }
                    }

                    // 3. High Fungus Risk Check Quest
                    if ($humidity >= 85 || $hadStorm) {
                        $fungusType = \App\Models\EventType::firstOrCreate(
                            ['code' => 'FUNGUS_CHECK'],
                            ['label' => 'Sanitasi Jamur Daun', 'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM']
                        );
                        $existingFungus = $events->first(fn($e) => $e->plant_id === $plant->id && $e->event_type_id === $fungusType->id);
                        if (!$existingFungus) {
                            $dynEvent = Event::firstOrCreate(
                                [
                                    'plant_id' => $plant->id,
                                    'event_type_id' => $fungusType->id,
                                    'scheduled_date' => Carbon::today()->toDateString(),
                                ],
                                [
                                    'status' => 'PENDING',
                                    'priority' => 'MEDIUM',
                                    'message' => "{$templateName}: Sanitasi Jamur Daun — Pangkas daun tua terbawah yang basah menyentuh tanah untuk mencegah antraknosa.",
                                ]
                            );
                            $dynEvent->load(['plant.plantTemplate', 'eventType', 'plant.garden']);
                            $events->push($dynEvent);
                        }
                    }
                }

                // Synchronize weather tags & advice for scheduled maintenance tasks
                foreach ($events as $event) {
                    $code = strtolower($event->eventType->code ?? '');

                    if ($event->status === 'PENDING') {
                        if (str_contains($code, 'water')) {
                            $event->weather_tag = $agronomic['watering']['badge'];
                            $event->weather_badge_bg = $agronomic['watering']['badge_bg'];
                            $event->weather_reason = $agronomic['watering']['advice'];

                            if ($agronomic['status'] === 'HEAT') {
                                $event->priority = 'HIGH';
                            }
                        } elseif (str_contains($code, 'fertiliz')) {
                            $event->weather_tag = $agronomic['fertilization']['badge'];
                            $event->weather_badge_bg = $agronomic['fertilization']['badge_bg'];
                            $event->weather_reason = $agronomic['fertilization']['advice'];

                            if (!$agronomic['fertilization']['allowed']) {
                                $event->priority = 'LOW';
                            }
                        } elseif (str_contains($code, 'pest')) {
                            $event->weather_tag = $agronomic['pest_disease']['badge'];
                            $event->weather_badge_bg = $agronomic['pest_disease']['badge_bg'];
                            $event->weather_reason = $agronomic['pest_disease']['advice'];
                        } elseif (str_contains($code, 'drainage')) {
                            $event->weather_tag = 'Cek Drainase Pasca Hujan';
                            $event->weather_badge_bg = 'bg-purple-100 text-purple-800';
                            $event->weather_reason = 'Mencegah pembusukan akar akibat genangan air pasca badai.';
                        } elseif (str_contains($code, 'fungus')) {
                            $event->weather_tag = 'Sanitasi Jamur Daun';
                            $event->weather_badge_bg = 'bg-teal-100 text-teal-800';
                            $event->weather_reason = "Kelembapan {$humidity}% memicu spora jamur daun.";
                        } elseif (str_contains($code, 'weather_protect')) {
                            $event->weather_tag = 'Proteksi Bibit (Cuaca Ekstrem)';
                            $event->weather_badge_bg = 'bg-rose-100 text-rose-800';
                            $event->weather_reason = "Melindungi batang bibit muda dari angin ({$windSpeed} km/j).";
                        } elseif (str_contains($code, 'staking')) {
                            $event->weather_tag = 'Kuatkan Ajir Penyangga';
                            $event->weather_badge_bg = 'bg-orange-100 text-orange-800';
                            $event->weather_reason = 'Memastikan tanaman tinggi tetap kokoh berdiri menghadapi cuaca.';
                        }
                    }
                }

                // Build Comprehensive Agronomic Weather Advice Banner
                $weatherAdvice = [
                    'title' => $agronomic['watering']['title'] . ' • ' . $agronomic['summary'],
                    'desc' => $agronomic['watering']['advice'] . ' ' . $agronomic['fertilization']['advice'],
                    'icon' => ($agronomic['status'] === 'RAIN' || $agronomic['status'] === 'THUNDERSTORM') ? 'rainy' : (($agronomic['status'] === 'HEAT') ? 'wb_sunny' : 'eco'),
                    'badge' => $agronomic['watering']['badge'],
                    'time_window' => $agronomic['watering']['time_window'],
                    'agronomic' => $agronomic
                ];
            }
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
            'agronomic' => $agronomic ?? null,
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
