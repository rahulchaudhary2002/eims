<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

// Student auth is in routes/auth.php (guest:student / auth:student)
require __DIR__ . '/auth.php';
require __DIR__ . '/admin/auth.php';
require __DIR__ . '/admin/web.php';
require __DIR__ . '/institution.php';

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('institution')->name('institution.')->group(function () {
    Route::get('/', [InstitutionController::class, 'index'])->name('index');
    Route::get('/{institution_slug}', [InstitutionController::class, 'show'])->name('show');
    Route::get('/{institution_slug}/query', [InstitutionController::class, 'query'])->name('query');
    Route::post('/{institution_slug}/query/store', [InstitutionController::class, 'storeQuery'])->name('query.store');
});

Route::prefix('program')->name('program.')->group(function () {
    Route::get('/', [ProgramController::class, 'index'])->name('index');
    Route::get('/{program:slug}', [ProgramController::class, 'show'])->name('show');
});
