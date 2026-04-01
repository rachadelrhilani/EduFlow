<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

// routes/web.php
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset'); // Ce nom est indispensable

Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/courses', function () { return view('courses.index'); });
Route::get('/favorites', function () { return view('favorites.index'); });