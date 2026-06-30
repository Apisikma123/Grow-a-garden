<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GardenPlot;
use App\Models\Garden;
use Illuminate\Support\Facades\Auth;

class GardenPlotController extends Controller
{
    public function index($garden_id)
    {
        $garden = Garden::where('user_id', Auth::id())->findOrFail($garden_id);
        $plots = GardenPlot::where('garden_id', $garden_id)->with('plant')->get();
        return response()->json($plots);
    }

    public function store(Request $request)
    {
        $request->validate([
            'garden_id' => 'required|exists:gardens,id',
            'name' => 'required|string|max:255',
            'shape' => 'required|in:rectangle,circle,hexagon,custom',
            'width' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'pos_x' => 'nullable|numeric',
            'pos_y' => 'nullable|numeric',
        ]);

        $garden = Garden::where('user_id', Auth::id())->findOrFail($request->garden_id);
        $user = Auth::user();

        // Enforce Limits
        $plotCount = GardenPlot::whereIn('garden_id', Garden::where('user_id', $user->id)->pluck('id'))->count();
        if ($user->role === 'free' && $plotCount >= 4) {
            return response()->json(['error' => 'Batas Paket Free: Maksimal 4 Plot secara keseluruhan.'], 403);
        } else if ($user->role === 'subur' && $plotCount >= 50) {
            return response()->json(['error' => 'Batas Paket Subur: Maksimal 50 Plot secara keseluruhan.'], 403);
        }

        $plot = GardenPlot::create($request->all());

        return response()->json($plot);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'width' => 'sometimes|numeric',
            'length' => 'sometimes|numeric',
            'pos_x' => 'sometimes|numeric',
            'pos_y' => 'sometimes|numeric',
            'plant_id' => 'sometimes|nullable|exists:plants,id',
        ]);

        $plot = GardenPlot::whereHas('garden', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $plot->update($request->all());

        return response()->json($plot);
    }

    public function destroy($id)
    {
        $plot = GardenPlot::whereHas('garden', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $plot->delete();

        return response()->json(['success' => true]);
    }
}
