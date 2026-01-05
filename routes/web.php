<?php

use App\Http\Controllers\CollegeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('course')->name('course')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
});

Route::prefix('college')->name('college')->group(function () {
    Route::get('/', [CollegeController::class, 'index']);
});

Route::prefix('school')->name('school')->group(function () {
    Route::get('/', [SchoolController::class, 'index']);
});

Route::prefix('forum/question')->name('forum.')->group(function () {
    Route::get('/', [QuestionController::class, 'index'])->name('question.index');
    Route::get('/{question}', [QuestionController::class, 'show'])->name('question.show');

    Route::middleware('auth')->group(function () {
        Route::get('/create', [QuestionController::class, 'create'])->name('question.create');
        Route::post('/', [QuestionController::class, 'store'])->name('question.store');
        Route::post('/{question}/reply', [ReplyController::class, 'store'])->name('reply.store');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin/auth.php';
require __DIR__ . '/admin/web.php';
require __DIR__ . '/vendor/auth.php';
require __DIR__ . '/vendor/web.php';
