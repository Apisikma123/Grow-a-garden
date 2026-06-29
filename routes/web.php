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

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
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
