<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WeatherService;

$service = new WeatherService();
$weather = $service->getTodayWeather(-6.2088, 106.8456);
echo "WEATHER FETCHED:\n";
print_r($weather);

$agronomic = $service->analyzeAgronomicConditions($weather);
echo "\nAGRONOMIC ANALYSIS:\n";
print_r($agronomic);
