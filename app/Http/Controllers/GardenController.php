<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garden;
use Illuminate\Support\Facades\Auth;

class GardenController extends Controller
{
    public function index()
    {
        $gardens = Garden::where('user_id', Auth::id())->with('plots')->get();
        
        $gardens->transform(function ($garden) {
            $garden->plots_count = $garden->plots->count();
            $area = 0;
            foreach ($garden->plots as $plot) {
                $area += round(($plot->width * $plot->length) / 100);
            }
            $garden->calculated_area_m2 = $area;
            unset($garden->plots); // Exclude plots from the JSON payload to keep it small
            return $garden;
        });

        return response()->json($gardens);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Enforce Limits
        if ($user->role === 'free') {
            $gardenCount = Garden::where('user_id', $user->id)->count();
            if ($gardenCount >= 1) {
                return response()->json(['error' => 'Batas Paket Free: Maksimal 1 Kebun.'], 403);
            }
        } else if ($user->role === 'subur') {
            $gardenCount = Garden::where('user_id', $user->id)->count();
            if ($gardenCount >= 10) {
                return response()->json(['error' => 'Batas Paket Subur: Maksimal 10 Kebun.'], 403);
            }
        }

        $garden = Garden::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'location_name' => $request->location,
        ]);

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
        ]);

        $garden->update([
            'name' => $request->name,
            'location_name' => $request->location,
        ]);

        return response()->json($garden);
    }

    public function destroy(Garden $garden)
    {
        if ($garden->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $garden->delete();

        return response()->json(['message' => 'Garden deleted successfully']);
    }
}
