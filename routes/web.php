<?php

use App\Http\Controllers\AdminPerawatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Login page
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register/perawat', [AuthController::class, 'showPerawatRegisterForm'])->name('register.perawat');
Route::post('/register/perawat', [AuthController::class, 'registerPerawat'])->name('register.perawat.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// ADMIN – daftar perawat
Route::get('/admin/perawat', [AdminPerawatController::class, 'index'])
    ->name('admin.perawat.index');

// ADMIN – detail per perawat
Route::get('/admin/perawat/{id}', [AdminPerawatController::class, 'show'])
    ->name('admin.perawat.show');
