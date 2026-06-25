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
