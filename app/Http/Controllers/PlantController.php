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
                $hst = $plantedDate ? max(0, (int) floor($plantedDate->diffInDays(now(), false))) : 0;

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

        if ($request->has('items') && is_array($request->items)) {
            $request->validate([
                'planted_date' => 'required|date',
                'items' => 'required|array|min:1',
                'items.*.plant_template_id' => 'required|exists:plant_templates,id',
                'items.*.quantity' => 'required|integer|min:1|max:100',
            ]);
            $items = $request->items;
        } else {
            $request->validate([
                'plant_template_id' => 'required|exists:plant_templates,id',
                'planted_date' => 'required|date',
                'quantity' => 'nullable|integer|min:1|max:100',
            ]);
            $items = [[
                'plant_template_id' => $request->plant_template_id,
                'quantity' => (int) $request->input('quantity', 1)
            ]];
        }

        $user = Auth::user();

        // Calculate total requested quantity across all items
        $totalQuantity = 0;
        foreach ($items as $item) {
            $totalQuantity += (int) $item['quantity'];
        }

        // Enforce plant limits based on user's plan
        $plantCount = Plant::whereIn('garden_id', Garden::where('user_id', $user->id)->pluck('id'))->count();
        $maxPlants = $user->maxPlants();
        
        if ($plantCount + $totalQuantity > $maxPlants) {
            $remaining = max(0, $maxPlants - $plantCount);
            return response()->json([
                'error' => "Batas Paket {$user->planName()}: Maksimal {$maxPlants} Tanaman. Sisa kuota Anda: {$remaining} tanaman, namun Anda mencoba menanam {$totalQuantity} tanaman. Upgrade untuk menambah kapasitas.",
                'limit_reached' => true,
                'current_plan' => $user->planName(),
                'remaining' => $remaining,
            ], 403);
        }

        $autopilot = new \App\Services\AutopilotService();

        foreach ($items as $item) {
            $qty = (int) $item['quantity'];
            $templateId = $item['plant_template_id'];

            for ($i = 0; $i < $qty; $i++) {
                $plant = Plant::create([
                    'garden_id' => $garden->id,
                    'plant_template_id' => $templateId,
                    'planted_date' => $request->planted_date,
                    'stage' => 'SEED',
                    'status' => 'ACTIVE',
                ]);

                // 🔥 Autopilot: auto-generate care tasks if user has autopilot access
                if ($autopilot) {
                    $plant->load('plantTemplate.category');
                    $autopilot->generateForPlant($plant);
                }
            }
        }

        // Auto-award badges via BadgeService
        $sync = \App\Services\BadgeService::syncUserBadges($user);
        $newBadge = null;
        if (!empty($sync['newlyAwardedIds'])) {
            $badge = \App\Models\Badge::find($sync['newlyAwardedIds'][0]);
            $newBadge = [
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
            ];
        }

        return response()->json([
            'success' => true,
            'count' => $totalQuantity,
            'new_badge' => $newBadge
        ], 201);
    }

    public function harvest($id)
    {
        $plant = Plant::whereHas('garden', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        if ($plant->status === 'FINISHED') {
            return response()->json(['error' => 'Tanaman ini sudah dipanen.'], 400);
        }

        $plant->update([
            'status' => 'FINISHED',
            'stage' => 'HARVEST',
        ]);

        // Cari tipe event HARVEST_READY jika ada, kalau tidak ya buat activity log dummy
        $harvestEventType = \App\Models\EventType::where('code', 'HARVEST_READY')->first();
        if ($harvestEventType) {
            \App\Models\Event::create([
                'plant_id' => $plant->id,
                'event_type_id' => $harvestEventType->id,
                'scheduled_date' => now(),
                'status' => 'COMPLETED',
                'priority' => 'HIGH',
                'message' => 'Berhasil memanen tanaman ini!',
                'completed_at' => now(),
            ]);
        }

        // Trigger badges (e.g. Panen Pertama)
        $user = Auth::user();
        $sync = \App\Services\BadgeService::syncUserBadges($user);
        $newBadge = null;
        if (!empty($sync['newlyAwardedIds'])) {
            $badge = \App\Models\Badge::find($sync['newlyAwardedIds'][0]);
            $newBadge = [
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
            ];
        }

        return response()->json(['success' => true, 'new_badge' => $newBadge]);
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
