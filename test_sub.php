<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
Auth::login($user);
$req = Illuminate\Http\Request::create('/api/subscribe', 'POST', ['plan' => 'pro', 'billing_cycle' => 'yearly']);
try {
    $res = app('App\Http\Controllers\SubscriptionController')->subscribe($req);
    echo $res->getContent();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
