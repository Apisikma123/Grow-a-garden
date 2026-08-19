<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventType;
use App\Models\Event;
use App\Models\Plant;

echo "=== EVENT TYPES ===\n";
foreach (EventType::all() as $t) {
    echo "ID: {$t->id} | Code: {$t->code} | Label: {$t->label} | Default Priority: {$t->default_priority}\n";
}

echo "\n=== CURRENT PLANTS & TODAY EVENTS ===\n";
$plants = Plant::with(['plantTemplate', 'events.eventType'])->get();
foreach ($plants as $p) {
    echo "PLANT: ID {$p->id} | {$p->name} ({$p->plantTemplate->name_id}) | Planted: {$p->planted_date}\n";
    foreach ($p->events as $e) {
        echo "  - EVENT ID {$e->id}: {$e->eventType->label} ({$e->eventType->code}) | Date: {$e->scheduled_date} | Status: {$e->status} | Msg: {$e->message}\n";
    }
}
