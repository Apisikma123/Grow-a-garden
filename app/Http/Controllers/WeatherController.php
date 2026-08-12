<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WeatherService;
use App\Models\Garden;

class WeatherController extends Controller
{
    public function live(Request $request, WeatherService $weatherService)
    {
        $user = Auth::user();
        $lat = 3.58;
        $lng = 98.67;

        if ($user) {
            $garden = Garden::where('user_id', $user->id)->first();
            if ($garden) {
                $lat = $garden->latitude ?? $lat;
                $lng = $garden->longitude ?? $lng;
            }
        }

        // Check if rain occurred earlier today in events/logs
        $hasRecentRain = false;
        if ($user) {
            $hasRecentRain = \App\Models\Event::whereHas('plant.garden', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereDate('updated_at', \Carbon\Carbon::today())
            ->where(function($q) {
                $q->where('status', 'SKIPPED')
                  ->orWhere('message', 'LIKE', '%hujan%');
            })
            ->exists();
        }

        $weather = $weatherService->getTodayWeather((float)$lat, (float)$lng);
        $agronomic = $weatherService->analyzeAgronomicConditions($weather, $hasRecentRain);

        return response()->json([
            'success' => true,
            'location' => [
                'latitude' => $lat,
                'longitude' => $lng,
            ],
            'agronomic' => $agronomic
        ]);
    }
}
