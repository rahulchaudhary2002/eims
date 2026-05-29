<?php

use App\Http\Controllers\Student\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Student auth routes
Route::middleware('guest:student')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('student.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:student')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('student.logout');
});

