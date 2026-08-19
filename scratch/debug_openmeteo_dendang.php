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
    'hourly' => 'temperature_2m,precipitation_probability,precipitation,weather_code,soil_moisture_0_to_1cm,soil_moisture_1_to_3cm',
    'daily' => 'weather_code,precipitation_sum,precipitation_probability_max',
    'timezone' => 'auto',
    'past_days' => 2,
    'forecast_days' => 2
]);

$data = $response->json();
echo "TIMEZONE: " . ($data['timezone'] ?? 'N/A') . "\n";
echo "CURRENT TIME: " . date('Y-m-d H:i:s') . "\n\n";

echo "DAILY PRECIPITATION:\n";
print_r($data['daily']);

echo "\nHOURLY DATA (PAST 24 HOURS TO NEXT 12 HOURS):\n";
$hourlyTimes = $data['hourly']['time'] ?? [];
$hourlyPrecip = $data['hourly']['precipitation'] ?? [];
$hourlyCode = $data['hourly']['weather_code'] ?? [];
$hourlyProb = $data['hourly']['precipitation_probability'] ?? [];
$soil01 = $data['hourly']['soil_moisture_0_to_1cm'] ?? [];
$soil13 = $data['hourly']['soil_moisture_1_to_3cm'] ?? [];

$nowIso = date('Y-m-d\TH:00');
$currentIdx = array_search($nowIso, $hourlyTimes);
if ($currentIdx === false) $currentIdx = count($hourlyTimes) - 1;

$startIdx = max(0, $currentIdx - 24);
$endIdx = min(count($hourlyTimes) - 1, $currentIdx + 12);

for ($i = $startIdx; $i <= $endIdx; $i++) {
    $isNow = ($i === $currentIdx) ? " <--- [NOW]" : "";
    $t = $hourlyTimes[$i];
    $p = $hourlyPrecip[$i] ?? 0;
    $c = $hourlyCode[$i] ?? 0;
    $pr = $hourlyProb[$i] ?? 0;
    $s1 = $soil01[$i] ?? 'N/A';
    echo "$t | Precip: {$p}mm | Code: {$c} | Prob: {$pr}% | Soil(0-1cm): {$s1}{$isNow}\n";
}
