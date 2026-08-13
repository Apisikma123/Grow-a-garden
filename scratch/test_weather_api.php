<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$response = Http::withoutVerifying()->get('https://api.open-meteo.com/v1/forecast', [
    'latitude' => -6.2088,
    'longitude' => 106.8456,
    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,showers,weather_code,cloud_cover,wind_speed_10m',
    'hourly' => 'temperature_2m,relative_humidity_2m,precipitation_probability,precipitation,weather_code,wind_speed_10m',
    'daily' => 'weather_code,temperature_2m_max,precipitation_probability_max',
    'timezone' => 'auto',
    'forecast_days' => 1
]);

if ($response->successful()) {
    $data = $response->json();
    echo "CURRENT DATA:\n";
    print_r($data['current']);
    echo "\nHOURLY PROBABILITY (NEXT 6 HOURS):\n";
    $currentHour = (int) date('H');
    $next6h = array_slice($data['hourly']['precipitation_probability'], $currentHour, 6);
    print_r($next6h);
    echo "Max 6h Rain Prob: " . max($next6h) . "%\n";
} else {
    echo "API FAIL\n";
}
