<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Garden;
use App\Models\User;
use App\Services\WeatherService;

$service = new WeatherService();

$gardens = Garden::with('user')->get();
echo "TOTAL GARDENS: " . count($gardens) . "\n\n";

foreach ($gardens as $g) {
    $lat = $g->latitude ?? 3.58;
    $lng = $g->longitude ?? 98.67;
    $weather = $service->getTodayWeather((float)$lat, (float)$lng);
    $agronomic = $service->analyzeAgronomicConditions($weather);
    
    echo "GARDEN ID: {$g->id} | Name: {$g->name} | Loc: {$g->location_name} ($lat, $lng)\n";
    echo "USER: " . ($g->user->name ?? 'N/A') . " (Role: " . ($g->user->role ?? 'N/A') . ")\n";
    echo "WEATHER: Temp {$weather['temperature']}C, Code {$weather['weather_code']}, RainProb {$weather['rain_probability']}%, Past12hPrecip {$weather['past_precipitation_12h']}mm, HadStorm: " . ($weather['had_recent_storm'] ? 'YES' : 'NO') . "\n";
    echo "DECISION: " . ($agronomic['watering']['title'] ?? 'N/A') . " | Action: " . ($agronomic['watering']['action'] ?? 'N/A') . "\n";
    echo "ADVICE: " . ($agronomic['watering']['advice'] ?? 'N/A') . "\n";
    echo "------------------------------------------------------------\n";
}
