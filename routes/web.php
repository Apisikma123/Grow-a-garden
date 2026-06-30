<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('users.dashboard');
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
    return view('users.checkout');
});

Route::get('/garden-plots', function () {
    return view('users.garden-plots');
});

Route::get('/growth-calendar', function () {
    return view('users.growth-calendar');
});

Route::get('/care-tasks', function () {
    return view('users.care-tasks');
});

Route::get('/settings', function () {
    return view('users.settings');
});

Route::get('/settings/password', function () {
    return view('users.settings-password');
});

Route::post('/settings/password', function () {
    // Implement password update logic here
    return redirect('/settings');
});

Route::post('/logout', function () {
    // Implement logout logic here
    return redirect('/');
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
