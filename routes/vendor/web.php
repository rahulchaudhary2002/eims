<?php

use App\Http\Controllers\Vendor\AdmissionApplicationController;
use App\Http\Controllers\Vendor\AdmissionController;
use App\Http\Controllers\Vendor\EnquiryController;
use App\Http\Controllers\Vendor\EventController;
use App\Http\Controllers\Vendor\InstitutionController;
use App\Http\Controllers\Vendor\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/institution/dashboard', function () {
    return view('vendor.modules.dashboard.index');
})->middleware(['auth:vendor', 'verified:vendor'])->name('vendor.dashboard');

Route::prefix('institution')->name('vendor.')->middleware(['auth:vendor'])->group(function () {
    Route::post('/set-current-institution', [SettingController::class, 'setCurrentInstitution'])
        ->name('set-current-institution');

    Route::prefix('institution')->name('institution.')->group(function () {
        Route::get('profile', [InstitutionController::class, 'profile'])->name('profile');
        Route::get('edit', [InstitutionController::class, 'edit'])->name('edit');
        Route::put('update', [InstitutionController::class, 'update'])->name('update');
    });

    Route::prefix('enquiry')->name('enquiry.')->group(function () {
        Route::get('/', [EnquiryController::class, 'index'])->name('index');
        Route::get('/{enquiry}', [EnquiryController::class, 'show'])->name('show');
        Route::post('/{enquiry}/reply', [EnquiryController::class, 'reply'])->name('reply');
    });

    Route::resource('admission', AdmissionController::class);

    Route::prefix('admission/{admission}')->name('admission.')->group(function () {
        Route::resource('application', AdmissionApplicationController::class)->only(['index', 'show']);
        Route::put('application/{application}/update-status', [AdmissionApplicationController::class, 'updateStatus'])
            ->name('application.update-status');
    });

    Route::resource('event', EventController::class);
});
