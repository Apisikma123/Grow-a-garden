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
    'daily' => 'weather_code,temperature_2m_max,precipitation_probability_max,precipitation_sum',
    'timezone' => 'auto',
    'past_days' => 1,
    'forecast_days' => 1
]);

if ($response->successful()) {
    $data = $response->json();
    echo "CURRENT TIME: " . date('Y-m-d H:i:s') . "\n";
    echo "DAILY PRECIPITATION SUM: \n";
    print_r($data['daily']);
    
    echo "\nHOURLY PRECIPITATION IN PAST 24 HOURS:\n";
    $hourlyTimes = $data['hourly']['time'];
    $hourlyPrecip = $data['hourly']['precipitation'];
    $hourlyCode = $data['hourly']['weather_code'];
    
    $nowIso = date('Y-m-d\TH:00');
    $currentIdx = array_search($nowIso, $hourlyTimes);
    if ($currentIdx === false) $currentIdx = count($hourlyTimes) - 1;
    
    $startIdx = max(0, $currentIdx - 12);
    $past12hSum = 0;
    $hasStormOrHeavyRain = false;
    
    for ($i = $startIdx; $i <= $currentIdx; $i++) {
        $time = $hourlyTimes[$i];
        $p = $hourlyPrecip[$i] ?? 0;
        $c = $hourlyCode[$i] ?? 0;
        $past12hSum += $p;
        if (in_array($c, [95, 96, 99, 65, 82]) || $p >= 2.0) {
            $hasStormOrHeavyRain = true;
        }
        echo "$time -> Precip: {$p}mm, Code: {$c}\n";
    }
    
    echo "\nTotal Past 12h Precip: {$past12hSum} mm\n";
    echo "Had Storm / Heavy Rain in past 12h: " . ($hasStormOrHeavyRain ? "YES" : "NO") . "\n";
} else {
    echo "FAIL\n";
}
