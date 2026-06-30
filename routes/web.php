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

Route::get('/checkout', function () {
    return view('checkout');
});

// Protected User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/garden-plots', function () {
        return view('garden-plots');
    });

    Route::get('/growth-calendar', function () {
        return view('growth-calendar');
    });

    Route::get('/care-tasks', function () {
        return view('care-tasks');
    });

    Route::get('/settings', function () {
        return view('settings');
    });
    
    Route::get('/settings/password', function () {
        return view('settings-password');
    });

    Route::post('/settings/password', function () {
        // Implement password update logic here
        return redirect('/settings');
    });
});

// Protected Admin Routes (We can add a custom 'admin' middleware later if needed)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/users', function () {
        return view('admin.users');
    });

    Route::get('/plants', function () {
        return view('admin.plants');
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

// Error Pages Preview Route
Route::get('/error-preview/{code}', function ($code) {
    if (in_array($code, ['404', '500', '403', 'offline'])) {
        return view("errors.$code");
    }
    abort(404);
});
