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
});
