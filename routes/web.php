<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('splash');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/users', function () {
    return view('admin.users');
});

Route::get('/admin/plants', function () {
    return view('admin.plants');
});

use App\Http\Controllers\TemplateController;

Route::get('/admin/care-templates', [TemplateController::class, 'index']);
Route::post('/admin/care-templates', [TemplateController::class, 'store']);
Route::put('/admin/care-templates/{template}', [TemplateController::class, 'update']);
Route::post('/admin/care-templates/{template}/duplicate', [TemplateController::class, 'duplicate']);
Route::delete('/admin/care-templates/{template}', [TemplateController::class, 'destroy']);

Route::get('/admin/weather', function () {
    return view('admin.weather');
});

Route::get('/admin/settings', function () {
    return view('admin.settings');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/checkout', function () {
    return view('checkout');
});

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

Route::post('/logout', function () {
    // Implement logout logic here
    return redirect('/');
});
