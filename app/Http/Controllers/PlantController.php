<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\Garden;
use App\Models\PlantTemplate;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    public function index($garden_id)
    {
        $garden = Garden::where('user_id', Auth::id())->findOrFail($garden_id);
        $plants = Plant::where('garden_id', $garden_id)
            ->with('plantTemplate.category')
            ->get()
            ->map(function ($plant) {
                $template = $plant->plantTemplate;
                $plantedDate = $plant->planted_date;
                $hst = $plantedDate ? (int) now()->diffInDays($plantedDate) : 0;

                // Determine current stage based on HST and template days
                $stage = $this->calculateStage($hst, $template);
                $estimatedHarvest = $template ? max(0, $template->harvest_start_day - $hst) : null;

                return [
                    'id' => $plant->id,
                    'garden_id' => $plant->garden_id,
                    'template_name' => $template->name_id ?? 'Unknown',
                    'scientific_name' => $template->scientific_name ?? '',
                    'category' => $template->category->name ?? '',
                    'planted_date' => $plantedDate?->format('Y-m-d'),
                    'hst' => $hst,
                    'stage' => $stage,
                    'status' => $plant->status,
                    'estimated_harvest_days' => $estimatedHarvest,
                    'harvest_start_day' => $template->harvest_start_day ?? null,
                    'harvest_end_day' => $template->harvest_end_day ?? null,
                    'template' => $template ? [
                        'germination_day' => $template->germination_day,
                        'seedling_day' => $template->seedling_day,
                        'vegetative_day' => $template->vegetative_day,
                        'flowering_day' => $template->flowering_day,
                        'fruiting_day' => $template->fruiting_day,
                        'harvest_start_day' => $template->harvest_start_day,
                        'harvest_end_day' => $template->harvest_end_day,
                        'multiple_harvest' => $template->multiple_harvest,
                        'water_requirement' => $template->water_requirement,
                        'sunlight' => $template->sunlight,
                        'soil_ph_min' => $template->soil_ph_min,
                        'soil_ph_max' => $template->soil_ph_max,
                    ] : null,
                ];
            });

        return response()->json($plants);
    }

    public function store(Request $request, $garden_id)
    {
        $garden = Garden::where('user_id', Auth::id())->findOrFail($garden_id);

        $request->validate([
            'plant_template_id' => 'required|exists:plant_templates,id',
            'planted_date' => 'required|date',
        ]);

        $user = Auth::user();

        // Enforce plant limits based on user's plan
        $plantCount = Plant::whereIn('garden_id', Garden::where('user_id', $user->id)->pluck('id'))->count();
        $maxPlants = $user->maxPlants();
        if ($plantCount >= $maxPlants) {
            return response()->json([
                'error' => "Batas Paket {$user->planName()}: Maksimal {$maxPlants} Tanaman. Upgrade untuk menambah kapasitas.",
                'limit_reached' => true,
                'current_plan' => $user->planName(),
            ], 403);
        }

        $plant = Plant::create([
            'garden_id' => $garden->id,
            'plant_template_id' => $request->plant_template_id,
            'planted_date' => $request->planted_date,
            'stage' => 'SEED',
            'status' => 'ACTIVE',
        ]);

        $plant->load('plantTemplate.category');

        // 🔥 Autopilot: auto-generate care tasks if user has autopilot access
        if ($user->canUseAutopilot()) {
            $autopilot = new \App\Services\AutopilotService();
            $autopilot->generateForPlant($plant);
        }

        return response()->json($plant, 201);
    }

    public function destroy($id)
    {
        $plant = Plant::whereHas('garden', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $plant->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $plant = Plant::whereHas('garden', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $request->validate([
            'planted_date' => 'required|date',
        ]);

        $plant->update([
            'planted_date' => $request->planted_date,
        ]);

        if ($request->wantsJson()) {
            return response()->json($plant);
        }

        return back()->with('success', 'Jadwal tanam berhasil diperbarui.');
    }

    private function calculateStage(int $hst, ?PlantTemplate $template): string
    {
        if (!$template) return 'SEED';

        if ($hst >= ($template->harvest_start_day ?? PHP_INT_MAX)) return 'HARVEST';
        if ($template->fruiting_day && $hst >= $template->fruiting_day) return 'FRUITING';
        if ($template->flowering_day && $hst >= $template->flowering_day) return 'FLOWERING';
        if ($template->vegetative_day && $hst >= $template->vegetative_day) return 'VEGETATIVE';
        if ($template->seedling_day && $hst >= $template->seedling_day) return 'SEEDLING';
        if ($template->germination_day && $hst >= $template->germination_day) return 'GERMINATION';

        return 'SEED';
    }
}
