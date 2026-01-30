<?php

use App\Http\Controllers\Admin\AdmissionCommissionController;
use App\Http\Controllers\Admin\AdmissionRewardController;
use App\Http\Controllers\Admin\AffiliationController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth:admin', 'verified:admin'])->name('dashboard');

    Route::resource('institution', InstitutionController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('affiliation', AffiliationController::class)->except('show');
    Route::resource('level', LevelController::class)->except('show');
    Route::resource('course', CourseController::class);

    Route::prefix('admission/reward')->name('admission.reward.')->group(function () {
        Route::get('/', [AdmissionRewardController::class, 'index'])->name('index');
        Route::put('/status/{reward}/approve', [AdmissionRewardController::class, 'approve'])->name('approve');
        Route::put('/status/{reward}/reject', [AdmissionRewardController::class, 'reject'])->name('reject');
    });

    Route::prefix('admission/commission')->name('admission.commission.')->group(function () {
        Route::get('/', [AdmissionCommissionController::class, 'index'])->name('index');
        Route::put('/mark-paid/{commission}', [AdmissionCommissionController::class, 'markAsPaid'])->name('markPaid');
    });

    Route::post('notification/read-all', [SettingController::class, 'readAllNotifications'])
        ->name('notification.read-all');
});
