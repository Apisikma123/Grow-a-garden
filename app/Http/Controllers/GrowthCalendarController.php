<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GrowthCalendarController extends Controller
{
    public function index(Request $request, \App\Services\WeatherService $weatherService, \App\Services\AutopilotService $autopilot)
    {
        $user = Auth::user();
        
        // Fetch all active plants for the user
        $plants = collect();
        if ($user) {
            $autopilot->generateForUser($user);

            $gardens = $user->gardens()->with(['plants' => function($query) {
                $query->whereNotIn('status', ['FINISHED', 'DEAD']);
            }, 'plants.plantTemplate'])->get();
            $plants = $gardens->pluck('plants')->flatten();
            foreach ($plants as $p) {
                $p->setRelation('garden', $gardens->firstWhere('id', $p->garden_id));
            }
        }

        if ($plants->isEmpty()) {
            return view('users.growth-calendar', [
                'mainPlant' => null,
                'otherPlants' => collect(),
                'timeline' => [],
                'todayTasks' => collect(),
                'agronomic' => null,
                'stageWeatherAdvice' => null,
                'isLocked' => false,
            ]);
        }

        // Determine main plant
        $mainPlantId = $request->query('plant_id');
        $mainPlant = $mainPlantId ? $plants->firstWhere('id', $mainPlantId) : $plants->sortByDesc('created_at')->first();

        if (!$mainPlant) {
            $mainPlant = $plants->first();
        }

        $otherPlants = $plants->where('id', '!=', $mainPlant->id);

        // Get coordinates for weather
        $lat = $mainPlant->garden->latitude ?? 3.58;
        $lng = $mainPlant->garden->longitude ?? 98.67;

        $weather = $weatherService->getTodayWeather((float)$lat, (float)$lng);
        $agronomic = $weatherService->analyzeAgronomicConditions($weather);

        // Generate weather-aware timeline
        $timeline = $this->generateTimeline($mainPlant, $currentHst, $agronomic);
        
        $isLocked = $user && ($user->role === 'free' || !$user->role);
        $todayTasks = collect();

        if ($mainPlant) {
            $todayTasks = \App\Models\Event::with('eventType')
                ->where('plant_id', $mainPlant->id)
                ->whereDate('scheduled_date', '<=', Carbon::today())
                ->whereIn('status', ['PENDING', 'MISSED'])
                ->orderBy('priority', 'asc')
                ->get();

            // Synchronize tasks with agronomic weather rules
            foreach ($todayTasks as $task) {
                $code = strtolower($task->eventType->code ?? '');
                if (str_contains($code, 'water')) {
                    $task->weather_tag = $agronomic['watering']['badge'];
                    $task->weather_badge_bg = $agronomic['watering']['badge_bg'];
                    $task->weather_reason = $agronomic['watering']['time_window'];
                    if ($agronomic['status'] === 'HEAT') $task->priority = 'HIGH';
                } elseif (str_contains($code, 'fertiliz')) {
                    $task->weather_tag = $agronomic['fertilization']['badge'];
                    $task->weather_badge_bg = $agronomic['fertilization']['badge_bg'];
                    $task->weather_reason = $agronomic['fertilization']['advice'];
                } elseif (str_contains($code, 'pest')) {
                    $task->weather_tag = $agronomic['pest_disease']['badge'];
                    $task->weather_badge_bg = $agronomic['pest_disease']['badge_bg'];
                    $task->weather_reason = $agronomic['pest_disease']['advice'];
                }
            }
        }

        // Build Phase & Weather Specific Guidance for the Main Plant
        $activeStage = collect($timeline)->firstWhere('status', 'active')['key'] ?? 'VEGETATIVE';
        $plantName = $mainPlant->plantTemplate->name_id ?? 'Tanaman';
        $temp = $agronomic['temperature'];
        $rainProb = $agronomic['rain_probability'];
        $status = $agronomic['status'];

        $stageAdviceText = '';
        if (in_array($activeStage, ['FLOWERING', 'FRUITING'])) {
            if ($status === 'HEAT') {
                $stageAdviceText = "Fase {$activeStage} ({$plantName}) & Suhu Panas ({$temp}°C): Semprotkan embun air halus di udara sekitar tajuk untuk mendinginkan mikroklimat agar bunga & calon buah tidak gugur.";
            } elseif ($status === 'RAIN') {
                $stageAdviceText = "Fase {$activeStage} ({$plantName}) & Hujan ({$rainProb}%): Waspada pembusukan calon buah & serangan antraknosa! Pastikan sirkulasi udara di sekitar perakaran lancar.";
            } else {
                $stageAdviceText = "Fase {$activeStage} ({$plantName}): Bunga & calon buah sedang berkembang. Jaga kelembapan tanah tetap stabil dan beri pupuk tinggi Kalium.";
            }
        } elseif (in_array($activeStage, ['SEED', 'GERMINATION', 'SEEDLING'])) {
            if ($status === 'HEAT') {
                $stageAdviceText = "Fase Persemaian ({$plantName}) & Suhu Panas ({$temp}°C): Bibit muda rentan layu permanen. Berikan naungan peneduh (paranet) atau pindahkan ke tempat teduh.";
            } elseif ($status === 'RAIN') {
                $stageAdviceText = "Fase Persemaian ({$plantName}) & Hujan: Lindungi nampan persemaian dari terpaan air hujan langsung agar benih tidak hanyut atau tumbang.";
            } else {
                $stageAdviceText = "Fase Persemaian ({$plantName}): Jaga kelembapan media semai dengan penyemprotan semprotan halus (sprayer).";
            }
        } else { // VEGETATIVE / HARVEST
            if ($status === 'HEAT') {
                $stageAdviceText = "Fase Vegetatif ({$plantName}) & Suhu Panas ({$temp}°C): Batang & daun tumbuh pesat. Berikan mulsa daun/jerami kering di atas permukaan tanah pot.";
            } elseif ($status === 'RAIN') {
                $stageAdviceText = "Fase Vegetatif ({$plantName}) & Hujan: Tunda pemupukan cair dan pangkas daun tua bagian bawah yang bersentuhan dengan tanah basah.";
            } else {
                $stageAdviceText = "Fase Vegetatif ({$plantName}): Fokus pertumbuhan daun & batang. Lakukan pemangkasan daun kuning secara berkala.";
            }
        }

        $stageWeatherAdvice = [
            'text' => $stageAdviceText,
            'stage' => $activeStage,
            'status' => $status
        ];

        return view('users.growth-calendar', [
            'mainPlant' => $mainPlant,
            'otherPlants' => $otherPlants,
            'timeline' => $timeline,
            'currentHst' => $currentHst,
            'todayTasks' => $todayTasks,
            'agronomic' => $agronomic,
            'stageWeatherAdvice' => $stageWeatherAdvice,
            'isLocked' => $isLocked,
        ]);
    }

    private function generateTimeline($plant, $currentHst, $agronomic = null)
    {
        $template = $plant->plantTemplate;
        $plantedDate = Carbon::parse($plant->planted_date);
        
        $stages = [
            ['key' => 'SEED', 'label' => 'Tanam', 'day' => 0, 'desc' => 'Benih disemai.'],
            ['key' => 'GERMINATION', 'label' => 'Germinasi', 'day' => $template->germination_day, 'desc' => 'Tunas pertama muncul.'],
            ['key' => 'SEEDLING', 'label' => 'Persemaian', 'day' => $template->seedling_day, 'desc' => 'Pertumbuhan awal bibit.'],
            ['key' => 'VEGETATIVE', 'label' => 'Vegetatif', 'day' => $template->vegetative_day, 'desc' => 'Fokus pada pertumbuhan batang dan daun.'],
        ];

        if ($template->flowering_day) {
            $stages[] = ['key' => 'FLOWERING', 'label' => 'Berbunga', 'day' => $template->flowering_day, 'desc' => 'Munculnya bunga.'];
        }
        
        if ($template->fruiting_day) {
            $stages[] = ['key' => 'FRUITING', 'label' => 'Berbuah', 'day' => $template->fruiting_day, 'desc' => 'Pembentukan buah.'];
        }

        $stages[] = ['key' => 'HARVEST', 'label' => 'Panen', 'day' => $template->harvest_start_day, 'desc' => 'Siap untuk dipanen.'];

        $timeline = [];
        $status = $agronomic['status'] ?? 'NORMAL';
        $temp = $agronomic['temperature'] ?? 29;
        $rainProb = $agronomic['rain_probability'] ?? 0;

        foreach ($stages as $index => $stage) {
            if ($stage['day'] === null) continue;

            $shiftedDays = $stage['day'];
            $weatherBadge = null;
            $weatherBadgeBg = '';
            $weatherDesc = $stage['desc'];

            if ($stage['day'] > 0) {
                if ($status === 'HEAT') {
                    // Hot weather speeds up metabolism & photosynthesis: harvest/stage 1-2 days earlier
                    $shift = max(1, (int)ceil($stage['day'] * 0.08)); // ~8% faster
                    $shiftedDays = max(1, $stage['day'] - $shift);
                    $weatherBadge = "Panas ({$temp}°C): Maju {$shift} Hari";
                    $weatherBadgeBg = "bg-amber-100 text-amber-800 border border-amber-200/60";
                    $weatherDesc = $stage['desc'] . " (Percepatan tumbuh akibat suhu terik & matahari tinggi).";
                } elseif ($status === 'RAIN') {
                    // Rainy/cloudy weather slows down light absorption & vegetative growth slightly: stage 1-2 days slower
                    $shift = max(1, (int)ceil($stage['day'] * 0.08)); // ~8% slower
                    $shiftedDays = $stage['day'] + $shift;
                    $weatherBadge = "Hujan ({$rainProb}%): Mundur {$shift} Hari";
                    $weatherBadgeBg = "bg-blue-100 text-blue-800 border border-blue-200/60";
                    $weatherDesc = $stage['desc'] . " (Est. tumbuh melambat akibat awan hujan & matahari kurang).";
                }
            }

            $date = $plantedDate->copy()->addDays($shiftedDays);
            $nextStageDay = isset($stages[$index + 1]) && $stages[$index + 1]['day'] !== null 
                                ? $stages[$index + 1]['day'] 
                                : 9999;
            
            $stageStatus = 'upcoming'; // default
            if ($currentHst >= $stage['day'] && $currentHst < $nextStageDay) {
                $stageStatus = 'active';
            } elseif ($currentHst >= $nextStageDay) {
                $stageStatus = 'completed';
            }
            
            if ($index == count($stages) - 1 && $currentHst >= $stage['day']) {
                $stageStatus = 'active'; 
            }

            $progress = 0;
            $daysLeft = 0;
            if ($stageStatus === 'active' && $nextStageDay != 9999) {
                $duration = $nextStageDay - $stage['day'];
                $passed = $currentHst - $stage['day'];
                $progress = min(100, max(0, ($passed / $duration) * 100));
                $daysLeft = max(0, $duration - $passed);
            } elseif ($stageStatus === 'active' && $nextStageDay == 9999) {
                $progress = 100;
            }

            $timeline[] = [
                'key' => $stage['key'],
                'label' => $stage['label'],
                'desc' => $weatherDesc,
                'weatherBadge' => $weatherBadge,
                'weatherBadgeBg' => $weatherBadgeBg,
                'date' => $date,
                'status' => $stageStatus,
                'progress' => $progress,
                'daysLeft' => $daysLeft
            ];
        }

        return $timeline;ne;
    }
}
