<?php

use App\Http\Controllers\Institution\Auth\RegisteredInstitutionController;
use App\Http\Controllers\Student\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Student\Auth\RegisteredStudentController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

// Institution registration (public, no guard required)
Route::post('institution/register', [RegisteredInstitutionController::class, 'store'])
    ->name('institution.register.store');

// Student guest routes (register / login)
Route::middleware('guest:student')->group(function () {
    Route::get('register', [RegisteredStudentController::class, 'create'])->name('register');
    Route::post('register', [RegisteredStudentController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('student.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Student authenticated routes
Route::middleware('auth:student')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('student.logout');

    // Dashboard - redirect legacy route to new student module
    Route::get('/student/dashboard', [\App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('student.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/extended', [ProfileController::class, 'updateExtended'])->name('profile.update-extended');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
