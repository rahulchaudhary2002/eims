<?php

use App\Http\Controllers\Admin\AffiliationController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('admin/dashboard', function () {
    return view('admin.modules.dashboard.index');
})->middleware(['auth:admin', 'verified:admin'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::resource('institution', InstitutionController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('affiliation', AffiliationController::class)->except('show');
    Route::resource('level', LevelController::class)->except('show');
    Route::resource('course', CourseController::class);
});
