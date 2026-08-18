<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Public or Guest routes added from HEAD
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp.show');
Route::post('/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('/checkout', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return view('users.checkout');
})->middleware('auth');

// Protected User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $gardens = \App\Models\Garden::where('user_id', Auth::id())->get();
        $gardenIds = $gardens->pluck('id');

        $activePlants = \App\Models\Plant::whereIn('garden_id', $gardenIds)
                            ->where('status', 'ACTIVE')
                            ->count();

        // Upcoming harvests (sort by estimated harvest days)
        $plants = \App\Models\Plant::whereIn('garden_id', $gardenIds)
                    ->where('status', 'ACTIVE')
                    ->with(['plantTemplate.category', 'garden'])
                    ->get();

        $upcomingHarvests = $plants->filter(function ($plant) {
            return $plant->estimated_harvest_days !== null && $plant->estimated_harvest_days <= 14;
        })->sortBy('estimated_harvest_days')->take(4);

        $plantIds = $plants->pluck('id');
        
        $todayTasks = \App\Models\Event::whereIn('plant_id', $plantIds)
            ->whereHas('eventType', function ($q) {
                $q->where('category', 'MAINTENANCE');
            })
            ->where('scheduled_date', '<=', now()->toDateString())
            ->where('status', 'PENDING')
            ->get();

        // 1. Plant Category Distribution
        $categoryCounts = [];
        $palette = ['#10b981', '#f97316', '#78a994', '#8b5cf6', '#ec4899', '#3b82f6', '#eab308'];
        $totalActivePlants = $plants->count();

        foreach ($plants as $plant) {
            $catName = ($plant->plantTemplate && $plant->plantTemplate->category) 
                ? $plant->plantTemplate->category->name 
                : 'Lainnya';
            if (!isset($categoryCounts[$catName])) {
                $categoryCounts[$catName] = 0;
            }
            $categoryCounts[$catName]++;
        }

        $plantDistribution = [];
        $gradientStops = [];
        $currentPct = 0;
        $colorIdx = 0;

        foreach ($categoryCounts as $catName => $count) {
            $pct = $totalActivePlants > 0 ? round(($count / $totalActivePlants) * 100, 1) : 0;
            $color = $palette[$colorIdx % count($palette)];
            $startPct = $currentPct;
            $endPct = $currentPct + $pct;
            $currentPct = $endPct;

            $gradientStops[] = "{$color} {$startPct}% {$endPct}%";

            $plantDistribution[] = [
                'name' => $catName,
                'count' => $count,
                'percentage' => $pct,
                'color' => $color,
            ];
            $colorIdx++;
        }

        $conicGradient = !empty($gradientStops) 
            ? 'conic-gradient(' . implode(', ', $gradientStops) . ')' 
            : 'conic-gradient(#e5e7eb 0% 100%)';

        // 2. Weekly Care Activity (Monday to Sunday of current week)
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);
        $todayStr = \Carbon\Carbon::today()->toDateString();

        $weekEvents = \App\Models\Event::whereIn('plant_id', $plantIds)
            ->whereBetween('scheduled_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->with('eventType')
            ->get();

        $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $weeklyDays = [];
        $maxDailyTotal = 0;

        $weeklyTotals = [
            'water' => 0,
            'fertilize' => 0,
            'prune' => 0,
            'total' => 0,
        ];

        for ($i = 0; $i < 7; $i++) {
            $dateCarbon = $startOfWeek->copy()->addDays($i);
            $dateStr = $dateCarbon->toDateString();
            
            $weeklyDays[$i] = [
                'day' => $dayLabels[$i],
                'date' => $dateStr,
                'isToday' => ($dateStr === $todayStr),
                'water' => 0,
                'fertilize' => 0,
                'prune' => 0,
                'total' => 0,
            ];
        }

        foreach ($weekEvents as $e) {
            if (!$e->scheduled_date) continue;
            $eventDateStr = $e->scheduled_date->toDateString();
            $eventCarbon = \Carbon\Carbon::parse($eventDateStr);
            $dayIdx = $eventCarbon->dayOfWeekIso - 1; // 0 (Mon) to 6 (Sun)

            if (isset($weeklyDays[$dayIdx])) {
                $code = $e->eventType ? $e->eventType->code : '';
                if (str_contains($code, 'WATER')) {
                    $weeklyDays[$dayIdx]['water']++;
                    $weeklyTotals['water']++;
                } elseif (str_contains($code, 'FERTILIZ')) {
                    $weeklyDays[$dayIdx]['fertilize']++;
                    $weeklyTotals['fertilize']++;
                } else {
                    $weeklyDays[$dayIdx]['prune']++;
                    $weeklyTotals['prune']++;
                }
                $weeklyDays[$dayIdx]['total']++;
                $weeklyTotals['total']++;

                if ($weeklyDays[$dayIdx]['total'] > $maxDailyTotal) {
                    $maxDailyTotal = $weeklyDays[$dayIdx]['total'];
                }
            }
        }

        // Calculate height percentages relative to maxDailyTotal
        $chartMax = max($maxDailyTotal, 1);
        foreach ($weeklyDays as &$dayData) {
            $dayData['heightPct'] = round(($dayData['total'] / $chartMax) * 100, 1);
            if ($dayData['total'] > 0) {
                $dayData['waterPct'] = round(($dayData['water'] / $dayData['total']) * 100, 1);
                $dayData['fertilizePct'] = round(($dayData['fertilize'] / $dayData['total']) * 100, 1);
                $dayData['prunePct'] = round(($dayData['prune'] / $dayData['total']) * 100, 1);
            } else {
                $dayData['waterPct'] = 0;
                $dayData['fertilizePct'] = 0;
                $dayData['prunePct'] = 0;
            }
        }
        unset($dayData);

        // 3. Active Alerts
        $activeAlerts = \Illuminate\Support\Facades\DB::table('alerts')
            ->whereIn('garden_id', $gardenIds)
            ->where('status', 'ACTIVE')
            ->orderBy('severity', 'asc')
            ->orderBy('triggered_at', 'desc')
            ->get();

        return view('users.dashboard', compact(
            'gardens', 
            'activePlants', 
            'upcomingHarvests', 
            'todayTasks', 
            'plantDistribution', 
            'conicGradient', 
            'weeklyDays', 
            'weeklyTotals',
            'activeAlerts'
        ));
    })->name('dashboard');

    Route::get('/gardens', function () {
        return view('users.gardens');
    })->name('gardens');

    Route::get('/growth-calendar', [\App\Http\Controllers\GrowthCalendarController::class, 'index'])->name('growth-calendar');

    Route::get('/care-tasks', [\App\Http\Controllers\CareTaskController::class, 'index'])->name('care-tasks');
    Route::patch('/care-tasks/{event}/complete', [\App\Http\Controllers\CareTaskController::class, 'complete'])->name('care-tasks.complete');
    Route::patch('/care-tasks/{event}/skip', [\App\Http\Controllers\CareTaskController::class, 'skip'])->name('care-tasks.skip');

    Route::get('/activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log');

    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::get('/badges', [\App\Http\Controllers\BadgeController::class, 'index'])->name('badges');
    Route::post('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    Route::delete('/settings/account', [\App\Http\Controllers\SettingsController::class, 'destroyAccount'])->name('settings.account.destroy');
    
    Route::get('/settings/password', [\App\Http\Controllers\SettingsController::class, 'showPassword'])->name('settings.password');
    Route::post('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password.update');

    // API Routes for Gardens
    Route::get('/api/gardens', [\App\Http\Controllers\GardenController::class, 'index']);
    Route::post('/api/gardens', [\App\Http\Controllers\GardenController::class, 'store']);
    Route::put('/api/gardens/{garden}', [\App\Http\Controllers\GardenController::class, 'update']);
    Route::delete('/api/gardens/{garden}', [\App\Http\Controllers\GardenController::class, 'destroy']);
    // API Routes for Plants
    Route::get('/api/gardens/{garden}/plants', [\App\Http\Controllers\PlantController::class, 'index']);
    Route::post('/api/gardens/{garden}/plants', [\App\Http\Controllers\PlantController::class, 'store']);
    Route::delete('/api/plants/{plant}', [\App\Http\Controllers\PlantController::class, 'destroy']);
    Route::post('/api/plants/{plant}/harvest', [\App\Http\Controllers\PlantController::class, 'harvest']);
    
    // Web route for Plants
    Route::put('/plants/{plant}', [\App\Http\Controllers\PlantController::class, 'update'])->name('plants.update');

    // API Routes for Plant Templates
    Route::get('/api/plant-templates', [\App\Http\Controllers\PlantTemplateController::class, 'index']);

    // Subscription API Routes
    Route::post('/api/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'subscribe']);
    Route::post('/api/cancel-subscription', [\App\Http\Controllers\SubscriptionController::class, 'cancel']);
    Route::get('/api/subscription-status', [\App\Http\Controllers\SubscriptionController::class, 'status']);

    // Live Weather API Route
    Route::get('/api/weather/live', [\App\Http\Controllers\WeatherController::class, 'live'])->name('api.weather.live');

    // Notifications
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark-read');

    // Admin API Routes
    Route::middleware(['admin'])->group(function () {
        Route::put('/api/admin/users/{user}/role', [\App\Http\Controllers\Admin\AdminController::class, 'updateRole']);
        Route::delete('/api/admin/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyUser']);
        
        Route::post('/api/admin/plants', [\App\Http\Controllers\Admin\AdminController::class, 'storePlant']);
        Route::put('/api/admin/plants/{plant}', [\App\Http\Controllers\Admin\AdminController::class, 'updatePlant']);
        Route::put('/api/admin/plants/{plant}/care-rules', [\App\Http\Controllers\Admin\AdminController::class, 'updateCareRules']);
        Route::delete('/api/admin/plants/{plant}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyPlant']);
    });
});

// Protected Admin Routes (We can add a custom 'admin' middleware later if needed)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('admin.users');

    Route::get('/plants', [\App\Http\Controllers\Admin\AdminController::class, 'plants'])->name('admin.plants');

    Route::get('/care-templates', [\App\Http\Controllers\Admin\AdminController::class, 'careTemplates'])->name('admin.care-templates');

    Route::get('/badges', [\App\Http\Controllers\Admin\AdminController::class, 'badges'])->name('admin.badges');
    Route::post('/badges', [\App\Http\Controllers\Admin\AdminController::class, 'storeBadge'])->name('admin.badges.store');
    Route::put('/badges/{badge}', [\App\Http\Controllers\Admin\AdminController::class, 'updateBadge'])->name('admin.badges.update');
    Route::delete('/badges/{badge}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyBadge'])->name('admin.badges.destroy');

    Route::get('/weather', [\App\Http\Controllers\Admin\AdminController::class, 'weather'])->name('admin.weather');
    Route::post('/weather/rules', [\App\Http\Controllers\Admin\AdminController::class, 'storeWeatherRule'])->name('admin.weather.rules.store');
    Route::put('/weather/rules/{weatherRule}', [\App\Http\Controllers\Admin\AdminController::class, 'updateWeatherRule'])->name('admin.weather.rules.update');
    Route::delete('/weather/rules/{weatherRule}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyWeatherRule'])->name('admin.weather.rules.destroy');
    
    Route::post('/weather/activity-rules', [\App\Http\Controllers\Admin\AdminController::class, 'storeActivityRule'])->name('admin.weather.activity.store');
    Route::put('/weather/activity-rules/{activityRule}', [\App\Http\Controllers\Admin\AdminController::class, 'updateActivityRule'])->name('admin.weather.activity.update');
    Route::delete('/weather/activity-rules/{activityRule}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyActivityRule'])->name('admin.weather.activity.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('admin.settings');
    Route::get('/settings/error-logs', [\App\Http\Controllers\Admin\AdminController::class, 'errorLogs']);
    Route::get('/settings/activity-logs', [\App\Http\Controllers\Admin\AdminController::class, 'activityLogs']);
    Route::get('/settings/login-logs', [\App\Http\Controllers\Admin\AdminController::class, 'loginLogs']);
    Route::post('/settings/clear-error-logs', [\App\Http\Controllers\Admin\AdminController::class, 'clearErrorLogs']);
    
    Route::get('/settings/password', [\App\Http\Controllers\SettingsController::class, 'showPassword'])->name('admin.settings.password');
    Route::post('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('admin.settings.password.update');
});

// Static Pages
Route::get('/learn', function () {
    return view('pages.learn');
});
Route::get('/sitemap', function () {
    return view('pages.sitemap');
});
Route::get('/privacy-policy', function () {
    return view('pages.privacy');
});
Route::get('/terms', function () {
    return view('pages.terms');
});

// Error Pages Preview Route
Route::get('/error-preview/{code}', function ($code) {
    if (in_array($code, ['404', '500', '403', 'offline'])) {
        return view("errors.$code");
    }
    abort(404);
});
