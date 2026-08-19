<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\CareTaskController;
use App\Services\WeatherService;
use App\Services\AutopilotService;

foreach (User::all() as $u) {
    echo "USER ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role} (Gardens: {$u->gardens()->count()})\n";
}

$porHup = User::where('name', 'like', '%Por%')->orWhere('email', 'like', '%por%')->first() ?? User::has('gardens')->first();
echo "\n--- TESTING WITH USER: {$porHup->name} (ID: {$porHup->id}) ---\n";
\Illuminate\Support\Facades\Auth::login($porHup);

$controller = new CareTaskController();
$request = Request::create('/care-tasks', 'GET');
$response = $controller->index($request, new WeatherService(), new AutopilotService());

$data = $response->getData();
$pending = $data['pendingTasks'];

echo "=== PENDING TASKS FOR TODAY (" . $pending->count() . " tasks) ===\n";
foreach ($pending as $t) {
    $code = $t->eventType->code ?? 'N/A';
    $label = $t->eventType->label ?? 'N/A';
    $tag = $t->weather_tag ?? 'NO_TAG';
    $reason = $t->weather_reason ?? 'NO_REASON';
    echo "ID: {$t->id} | {$label} ({$code}) | Priority: {$t->priority}\n";
    echo "  Msg: {$t->message}\n";
    echo "  WeatherTag: {$tag} | Reason: {$reason}\n";
    echo "--------------------------------------------------------\n";
}
