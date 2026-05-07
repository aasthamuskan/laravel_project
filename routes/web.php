<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AdvisoryController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ─── Language Switcher ────────────────────────────────────────────────────────
Route::get('/lang/{locale}', function (string $locale) {
    $supported = ['en','hi','pa','bn','ta','te','mr','gu','kn','ml'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Web Routes — Weather Forecast & Farming Advisory System
|--------------------------------------------------------------------------
|
| Route groups:
|   Public             → welcome page, auth routes
|   auth               → dashboard, profile, weather API, advisory filter
|   auth + role:Expert → advisory CRUD
|   auth + role:Admin  → admin panel
|
*/

// ─── Public ──────────────────────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('dashboard'))->name('home');

// ─── Authenticated: All roles ─────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — GET /dashboard?city=&crop_id=&season=
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management (Breeze default)
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Weather JSON API — GET /weather?city=London
    Route::get('/weather', [WeatherController::class, 'show'])->name('weather.show');

    // Advisory filter (farmer-facing) — GET /advisory?crop_id=&season=
    Route::get('/advisory', [AdvisoryController::class, 'filter'])->name('advisory.filter');
});

// ─── Expert: Advisory CRUD ────────────────────────────────────────────────────
// Experts manage the advisory database entries
Route::middleware(['auth', 'role:Expert,Admin'])->group(function () {
    Route::resource('advisories', AdvisoryController::class)
         ->except(['show']);  // No single-advisory detail page needed
});

// ─── Admin: System management ────────────────────────────────────────────────
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/overview', [AdminController::class, 'overview'])->name('overview');
    Route::get('/users',    [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/role',   [AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}',       [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/advisories',            [AdminController::class, 'advisories'])->name('advisories');
    Route::delete('/advisories/{advisory}', [AdminController::class, 'deleteAdvisory'])->name('advisories.destroy');
});

// ─── Breeze Auth Routes ───────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
