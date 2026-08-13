<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garden;
use Illuminate\Support\Facades\Auth;

class GardenController extends Controller
{
    public function index()
    {
        $gardens = Garden::where('user_id', Auth::id())
            ->withCount('plants')
            ->get();

        return response()->json($gardens);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = Auth::user();

        // Enforce garden limits based on user's plan
        $gardenCount = Garden::where('user_id', $user->id)->count();
        $maxGardens = $user->maxGardens();
        if ($gardenCount >= $maxGardens) {
            return response()->json([
                'error' => "Batas Paket {$user->planName()}: Maksimal {$maxGardens} Kebun. Upgrade untuk menambah kapasitas.",
                'limit_reached' => true,
                'current_plan' => $user->planName(),
            ], 403);
        }

        $garden = Garden::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'location_name' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Auto-award badges (like 'Pekebun Pertama') via BadgeService
        $sync = \App\Services\BadgeService::syncUserBadges($user);
        if (!empty($sync['newlyAwardedIds'])) {
            $badge = \App\Models\Badge::find($sync['newlyAwardedIds'][0]);
            $garden->new_badge = [
                'name' => $badge->name,
                'description' => $badge->description,
                'icon_url' => $badge->icon_url,
            ];
        }

        $garden->loadCount('plants');

        return response()->json($garden);
    }

    public function update(Request $request, Garden $garden)
    {
        if ($garden->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $garden->update([
            'name' => $request->name,
            'location_name' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $garden->loadCount('plants');

        return response()->json($garden);
    }

    public function destroy(Garden $garden)
    {
        if ($garden->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $garden->delete();
        return response()->json(['success' => true, 'message' => 'Garden deleted successfully']);
    }
}
