<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$lat = 3.6139923;
$lng = 98.7297630;

$response = Http::withoutVerifying()->get('https://api.open-meteo.com/v1/forecast', [
    'latitude' => $lat,
    'longitude' => $lng,
    'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,showers,weather_code,cloud_cover,wind_speed_10m',
    'hourly' => 'temperature_2m,relative_humidity_2m,precipitation_probability,precipitation,weather_code,wind_speed_10m,cloud_cover',
    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max,precipitation_sum',
    'timezone' => 'auto',
    'past_days' => 1,
    'forecast_days' => 1
]);

$data = $response->json();
$current = $data['current'] ?? [];
$hourly = $data['hourly'] ?? [];
$daily = $data['daily'] ?? [];

$hourlyTimes = $hourly['time'] ?? [];
$nowIsoHour = date('Y-m-d\TH:00');
$hourIndex = array_search($nowIsoHour, $hourlyTimes);
if ($hourIndex === false) $hourIndex = count($hourlyTimes) - 1;

// Past 24h scan
$startPastIdx = max(0, $hourIndex - 24);
$past24hPrecip = 0.0;
$pastHadStorm = false;
for ($i = $startPastIdx; $i <= $hourIndex; $i++) {
    $p = (float) ($hourly['precipitation'][$i] ?? 0);
    $c = (int) ($hourly['weather_code'][$i] ?? 0);
    $past24hPrecip += $p;
    if (in_array($c, [95, 96, 99, 65, 82]) || $p >= 3.0) {
        $pastHadStorm = true;
    }
}
$yesterdayCode = (int) ($daily['weather_code'][0] ?? 0);
$yesterdayPrecip = (float) ($daily['precipitation_sum'][0] ?? 0);
if (in_array($yesterdayCode, [95, 96, 99, 65, 82]) || $yesterdayPrecip >= 5.0) {
    $pastHadStorm = true;
}

// Next 12h scan
$endFutureIdx = min(count($hourlyTimes) - 1, $hourIndex + 12);
$futureMaxProb = 0;
$futureMaxPrecip = 0.0;
$futureHasStorm = false;
$futureStormHour = null;
for ($i = $hourIndex; $i <= $endFutureIdx; $i++) {
    $prob = (int) ($hourly['precipitation_probability'][$i] ?? 0);
    $p = (float) ($hourly['precipitation'][$i] ?? 0);
    $c = (int) ($hourly['weather_code'][$i] ?? 0);
    if ($prob > $futureMaxProb) $futureMaxProb = $prob;
    if ($p > $futureMaxPrecip) $futureMaxPrecip = $p;
    if (in_array($c, [95, 96, 99, 65, 81, 82]) || $p >= 2.0) {
        $futureHasStorm = true;
        if (!$futureStormHour) $futureStormHour = $hourlyTimes[$i];
    }
}

$todayDailyCode = (int) ($daily['weather_code'][1] ?? 0);
$todayDailyPrecip = (float) ($daily['precipitation_sum'][1] ?? 0);
$todayDailyMaxProb = (int) ($daily['precipitation_probability_max'][1] ?? 0);

echo "PAST 24H: Precip {$past24hPrecip}mm, HadStorm: " . ($pastHadStorm ? 'YES' : 'NO') . ", YesterdayCode: $yesterdayCode, YesterdayPrecip: {$yesterdayPrecip}mm\n";
echo "UPCOMING 12H: MaxProb {$futureMaxProb}%, MaxPrecip {$futureMaxPrecip}mm, HasStorm: " . ($futureHasStorm ? 'YES' : 'NO') . " (At $futureStormHour)\n";
echo "TODAY DAILY: Code $todayDailyCode, PrecipSum {$todayDailyPrecip}mm, MaxProb {$todayDailyMaxProb}%\n";
