<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login.form');
});

// Admin routes
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [LoginController::class, 'registrationStore'])->name('register.post');
Route::get('/login', [LoginController::class, 'login'])->name('login.form');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware('role:web')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::group(['prefix' => '/', 'as' => 'front.'], function () {
    // Route::get('/', [\App\Http\Controllers\FrontController::class, 'home'])->name('home');
    Route::get('/room/{id}', [\App\Http\Controllers\FrontController::class, 'roomDetailPage'])->name('room.detail.page');
    Route::get('/book-room/{id?}', [\App\Http\Controllers\FrontController::class, 'openRoomBookingPage'])->name('room.book');
    Route::post('/booking/store', [\App\Http\Controllers\FrontController::class, 'roomBookingStore'])->name('room.store');
    Route::get('/about', [\App\Http\Controllers\FrontController::class, 'aboutUs'])->name('about');
    Route::get('/room', [\App\Http\Controllers\FrontController::class, 'room'])->name('room');
    Route::get('/gallery', [\App\Http\Controllers\FrontController::class, 'gallery'])->name('gallery');
    Route::get('/blog', [\App\Http\Controllers\FrontController::class, 'blog'])->name('blog');
    Route::get('/privacy-policy', [\App\Http\Controllers\FrontController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('/terms-condition', [\App\Http\Controllers\FrontController::class, 'termsCondition'])->name('terms.condition');
    Route::get('/contact-us', [\App\Http\Controllers\FrontController::class, 'contactUs'])->name('contact');
    Route::post('/save-contact-us', [\App\Http\Controllers\FrontController::class, 'saveContactUs'])->name('save.contact');
});

Route::get('lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switchLang']);

// require __DIR__ . '/auth.php';
