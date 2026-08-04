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
    return view('users.checkout');
});

// Protected User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $gardens = \App\Models\Garden::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        $gardenIds = $gardens->pluck('id');

        $activePlants = \App\Models\Plant::whereIn('garden_id', $gardenIds)
                            ->where('status', 'ACTIVE')
                            ->count();

        // Upcoming harvests (sort by estimated harvest days)
        $plants = \App\Models\Plant::whereIn('garden_id', $gardenIds)
                    ->where('status', 'ACTIVE')
                    ->with(['plantTemplate', 'garden'])
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
        
        return view('users.dashboard', compact('gardens', 'activePlants', 'upcomingHarvests', 'todayTasks'));
    })->name('dashboard');

    Route::get('/gardens', function () {
        return view('users.gardens');
    })->name('gardens');

    Route::get('/growth-calendar', function (\Illuminate\Http\Request $request) {
        $gardens = \App\Models\Garden::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        $gardenIds = $gardens->pluck('id');

        $plants = \App\Models\Plant::whereIn('garden_id', $gardenIds)
                    ->with(['plantTemplate.category', 'garden'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $selectedPlant = null;
        if ($request->has('plant_id')) {
            $selectedPlant = $plants->firstWhere('id', $request->plant_id);
        }
        if (!$selectedPlant && $plants->isNotEmpty()) {
            $selectedPlant = $plants->first();
        }

        return view('users.growth-calendar', compact('plants', 'selectedPlant'));
    });

    Route::get('/care-tasks', function (\Illuminate\Http\Request $request) {
        $gardens = \App\Models\Garden::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
        $plantIds = \App\Models\Plant::whereIn('garden_id', $gardens->pluck('id'))->pluck('id');

        // Fetch all maintenance tasks scheduled up to today
        $allTasks = \App\Models\Event::whereIn('plant_id', $plantIds)
            ->whereHas('eventType', function ($q) {
                $q->where('category', 'MAINTENANCE');
            })
            ->where('scheduled_date', '<=', now()->toDateString())
            ->with(['eventType', 'plant.plantTemplate', 'plant.garden'])
            ->orderBy('scheduled_date', 'asc')
            ->get();
            
        $completedTasks = $allTasks->where('status', 'COMPLETED');
        $pendingTasks = $allTasks->whereIn('status', ['PENDING', 'MISSED']);
        $highPriorityCount = $pendingTasks->where('priority', 'HIGH')->count();

        return view('users.care-tasks', compact('pendingTasks', 'completedTasks', 'allTasks', 'highPriorityCount'));
    });

    Route::get('/settings', function () {
        return view('users.settings');
    });

    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/settings/password', function () {
        return view('users.settings-password');
    });

    Route::post('/settings/password', function () {
        // Implement password update logic here
        return redirect('/settings');
    });

    // API Routes for Gardens
    Route::get('/api/gardens', [\App\Http\Controllers\GardenController::class, 'index']);
    Route::post('/api/gardens', [\App\Http\Controllers\GardenController::class, 'store']);
    Route::put('/api/gardens/{garden}', [\App\Http\Controllers\GardenController::class, 'update']);
    Route::delete('/api/gardens/{garden}', [\App\Http\Controllers\GardenController::class, 'destroy']);

    // API Routes for Plants
    Route::get('/api/gardens/{garden}/plants', [\App\Http\Controllers\PlantController::class, 'index']);
    Route::post('/api/gardens/{garden}/plants', [\App\Http\Controllers\PlantController::class, 'store']);
    Route::delete('/api/plants/{plant}', [\App\Http\Controllers\PlantController::class, 'destroy']);

    // API Routes for Plant Templates
    Route::get('/api/plant-templates', [\App\Http\Controllers\PlantTemplateController::class, 'index']);

});

// Protected Admin Routes (We can add a custom 'admin' middleware later if needed)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $totalUsers = \App\Models\User::count();
        $totalGardens = \App\Models\Garden::count();
        $totalPlants = \App\Models\Plant::count();
        
        $successfulHarvests = \App\Models\Event::whereHas('eventType', function($q) {
            $q->where('category', 'HARVEST');
        })->where('status', 'COMPLETED')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalGardens', 'totalPlants', 'successfulHarvests'));
    })->name('admin.dashboard');

    Route::get('/plants', function () {
        $plantTemplates = \App\Models\PlantTemplate::with('category')->get();
        return view('admin.plants', compact('plantTemplates'));
    });

    Route::get('/users', function () {
        return view('admin.users');
    });

    Route::get('/care-templates', function () {
        return view('admin.care-templates');
    });

    Route::get('/weather', function () {
        return view('admin.weather');
    });

    Route::get('/settings', function () {
        return view('admin.settings');
    });
    
    Route::get('/settings/password', function () {
        return view('admin.settings-password');
    });

    Route::post('/settings/password', function () {
        // Implement password update logic here
        return redirect('/admin/settings');
    });
});

// Static Pages
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
