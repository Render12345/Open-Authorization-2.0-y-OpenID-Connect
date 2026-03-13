<?php

use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', fn () => view('auth.login'))->name('login');

// ── OAuth 2.0 flow ────────────────────────────────────────────
//  Step 1: redirect user → provider
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
    ->name('auth.redirect');

//  Step 2: provider redirects back with code
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
    ->name('auth.callback');

// ── Protected ─────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', fn () => view('auth.dashboard'))->name('dashboard');

    Route::post('/logout', [SocialAuthController::class, 'logout'])->name('logout');
});
