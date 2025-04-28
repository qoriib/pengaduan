<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::post('/logout', [AuthController::class, 'handleLogout'])->name('logout.handle');

// Contoh setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');
