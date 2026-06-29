<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

Route::get('/admin/care-templates', function () {
    return view('admin.care-templates');
});

Route::get('/admin/weather', function () {
    return view('admin.weather');
});

Route::get('/admin/settings', function () {
    return view('admin.settings');
});

Route::get('/admin/settings/password', function () {
    return view('admin.settings-password');
});

Route::post('/admin/settings/password', function () {
    // Implement password update logic here
    return redirect('/admin/settings');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/otp', function () {
    return view('auth.otp');
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

Route::get('/settings/password', function () {
    return view('settings-password');
});

Route::post('/settings/password', function () {
    // Implement password update logic here
    return redirect('/settings');
});

Route::post('/logout', function () {
    // Implement logout logic here
    return redirect('/');
});
// Error Pages Preview Route
Route::get('/error-preview/{code}', function ($code) {
    if (in_array($code, ['404', '500', '403', 'offline'])) {
        return view("errors.$code");
    }
    abort(404);
});
