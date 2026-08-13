<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$weatherRules = DB::table('weather_rules')->get();
foreach ($weatherRules as $rule) {
    $clean = preg_replace('/[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{FE00}-\x{FE0F}]/u', '', $rule->message);
    $clean = trim($clean);
    DB::table('weather_rules')->where('id', $rule->id)->update(['message' => $clean]);
}

$activityRules = DB::table('activity_weather_rules')->get();
foreach ($activityRules as $rule) {
    $clean = preg_replace('/[\x{1F300}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{FE00}-\x{FE0F}]/u', '', $rule->message);
    $clean = trim($clean);
    DB::table('activity_weather_rules')->where('id', $rule->id)->update(['message' => $clean]);
}

echo "Database emojis cleaned successfully.\n";
