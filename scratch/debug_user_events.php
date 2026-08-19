<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Event;
use Carbon\Carbon;

$user = User::first();
echo "USER ID: {$user->id} | Name: {$user->name} | Role: {$user->role}\n";

$gardens = $user->gardens()->with('plants')->get();
echo "GARDENS: " . $gardens->count() . "\n";
foreach ($gardens as $g) {
    echo "Garden: {$g->id} - {$g->name} (Plants: {$g->plants->count()})\n";
    foreach ($g->plants as $p) {
        echo "  Plant: {$p->id} - {$p->name} | Planted: {$p->planted_date}\n";
    }
}

$today = Carbon::today()->toDateString();
echo "TODAY DATE: {$today}\n";

$allEventsToday = Event::whereDate('scheduled_date', $today)->get();
echo "ALL EVENTS SCHEDULED TODAY: " . $allEventsToday->count() . "\n";
foreach ($allEventsToday as $e) {
    echo "  - Event ID {$e->id}: Type {$e->event_type_id} | Status {$e->status} | Msg: {$e->message}\n";
}
