<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GrowthCalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Fetch all active plants for the user
        $plants = collect();
        if ($user) {
            $plants = $user->gardens()->with(['plants' => function($query) {
                $query->whereNotIn('status', ['FINISHED', 'DEAD']);
            }, 'plants.plantTemplate'])->get()->pluck('plants')->flatten();
        }

        if ($plants->isEmpty()) {
            return view('users.growth-calendar', [
                'mainPlant' => null,
                'otherPlants' => collect(),
                'timeline' => []
            ]);
        }

        // Determine main plant
        $mainPlantId = $request->query('plant_id');
        $mainPlant = $mainPlantId ? $plants->firstWhere('id', $mainPlantId) : $plants->sortByDesc('created_at')->first();

        if (!$mainPlant) {
            $mainPlant = $plants->first();
        }

        $otherPlants = $plants->where('id', '!=', $mainPlant->id);

        // Generate timeline
        $timeline = $this->generateTimeline($mainPlant);

        return view('users.growth-calendar', [
            'mainPlant' => $mainPlant,
            'otherPlants' => $otherPlants,
            'timeline' => $timeline
        ]);
    }

    private function generateTimeline($plant)
    {
        $template = $plant->plantTemplate;
        $plantedDate = Carbon::parse($plant->planted_date);
        $today = Carbon::now();
        $currentHst = $plantedDate->diffInDays($today, false); // can be negative if future

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

        foreach ($stages as $index => $stage) {
            if ($stage['day'] === null) continue;

            $date = $plantedDate->copy()->addDays($stage['day']);
            $nextStageDay = isset($stages[$index + 1]) && $stages[$index + 1]['day'] !== null 
                                ? $stages[$index + 1]['day'] 
                                : 9999;
            
            $status = 'upcoming'; // default
            if ($currentHst >= $stage['day'] && $currentHst < $nextStageDay) {
                $status = 'active';
            } elseif ($currentHst >= $nextStageDay) {
                $status = 'completed';
            }
            
            if ($index == count($stages) - 1 && $currentHst >= $stage['day']) {
                $status = 'active'; 
            }

            $progress = 0;
            $daysLeft = 0;
            if ($status === 'active' && $nextStageDay != 9999) {
                $duration = $nextStageDay - $stage['day'];
                $passed = $currentHst - $stage['day'];
                $progress = min(100, max(0, ($passed / $duration) * 100));
                $daysLeft = max(0, $duration - $passed);
            } elseif ($status === 'active' && $nextStageDay == 9999) {
                $progress = 100;
            }

            $timeline[] = [
                'key' => $stage['key'],
                'label' => $stage['label'],
                'desc' => $stage['desc'],
                'date' => $date,
                'status' => $status,
                'progress' => $progress,
                'daysLeft' => $daysLeft
            ];
        }

        return $timeline;
    }
}
