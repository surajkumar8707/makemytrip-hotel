<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendors\Auth\LoginController;
use App\Http\Controllers\Vendors\VendorController;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "Admin" middleware group. Make something great!
|
*/

// Admin routes
Route::get('/register', [LoginController::class, 'showVendorRegisterForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'vendorRegister'])->name('register.form');
Route::get('/login', [LoginController::class, 'showVendorLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'vendorLogin'])->name('login.post');
Route::post('/logout', [LoginController::class, 'vendorLogout'])->name('logout');

// Authenticated routes
Route::middleware('role:vendor')->group(function () {
    Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/cache-clear', [VendorController::class, 'cacheClear'])->name('cache.clear')->middleware('web');

    Route::get('/profile', [VendorController::class, 'profile'])->name('profile');
    Route::post('/profile', [VendorController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/change-password', [VendorController::class, 'changePassword'])->name('password');
    Route::post('/update-password', [VendorController::class, 'updatePassword'])->name('update.password');
    Route::get('/setting', [VendorController::class, 'setting'])->name('setting');
    Route::post('/setting-update/{id}', [VendorController::class, 'settingUpdate'])->name('setting.update');
});
