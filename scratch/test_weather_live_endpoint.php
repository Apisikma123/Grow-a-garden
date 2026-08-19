<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\WeatherController;
use App\Services\WeatherService;

$user = User::first();
\Illuminate\Support\Facades\Auth::login($user);

$controller = new WeatherController();
$service = new WeatherService();

$request = Request::create('/api/weather/live?lat=3.6139923&lng=98.7297630', 'GET');
$response = $controller->live($request, $service);

echo "API RESPONSE:\n";
echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
