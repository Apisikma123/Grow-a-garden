<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\EventType;

$newTypes = [
    ['code' => 'WEATHER_PROTECTION', 'label' => 'Proteksi Cuaca Ekstrem', 'category' => 'MAINTENANCE', 'default_priority' => 'HIGH'],
    ['code' => 'DRAINAGE_CHECK', 'label' => 'Pemeriksaan Drainase Pot', 'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM'],
    ['code' => 'WEEDING', 'label' => 'Penyiangan Gulma & Aerasi', 'category' => 'MAINTENANCE', 'default_priority' => 'LOW'],
    ['code' => 'FUNGUS_CHECK', 'label' => 'Sanitasi Jamur Daun', 'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM'],
    ['code' => 'NEEM_SPRAY', 'label' => 'Aplikasi Pestisida Nabati', 'category' => 'MAINTENANCE', 'default_priority' => 'MEDIUM'],
];

foreach ($newTypes as $t) {
    EventType::updateOrCreate(
        ['code' => $t['code']],
        $t
    );
    echo "Event Type synced: {$t['code']} - {$t['label']}\n";
}
