<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    /**
     * Get today's weather forecast for a specific location.
     * Uses Open-Meteo API (Free, no API key required).
     */
    public function getTodayWeather(float $latitude, float $longitude): ?array
    {
        // Round lat/long to 2 decimal places to increase cache hit rate for nearby locations
        $lat = round($latitude, 2);
        $lng = round($longitude, 2);
        
        $cacheKey = "weather_{$lat}_{$lng}_" . now()->format('Y-m-d');
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($lat, $lng) {
            try {
                $response = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'daily' => 'temperature_2m_max,precipitation_probability_max,wind_speed_10m_max,relative_humidity_2m_mean',
                    'timezone' => 'Asia/Jakarta',
                    'forecast_days' => 1
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!isset($data['daily'])) {
                        return null;
                    }
                    
                    return [
                        'temperature' => $data['daily']['temperature_2m_max'][0] ?? null,
                        'rain_probability' => $data['daily']['precipitation_probability_max'][0] ?? null,
                        'wind_speed' => $data['daily']['wind_speed_10m_max'][0] ?? null,
                        'humidity' => $data['daily']['relative_humidity_2m_mean'][0] ?? null,
                    ];
                }
                
                Log::error('Weather API failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            } catch (\Exception $e) {
                Log::error('Weather API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }
}
