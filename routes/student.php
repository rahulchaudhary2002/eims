<?php

use App\Http\Controllers\Student\StudentAcademicRecordController;
use App\Http\Controllers\Student\StudentApplicationController;
use App\Http\Controllers\Student\StudentCashbackController;
use App\Http\Controllers\Student\StudentConversationController;
use App\Http\Controllers\Student\StudentCounselingSessionController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentDocumentController;
use App\Http\Controllers\Student\StudentFavoriteInstitutionController;
use App\Http\Controllers\Student\StudentMessageController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentRecommendationController;
use App\Http\Controllers\Student\StudentReviewController;
use App\Http\Controllers\Student\StudentScholarshipApplicationController;
use App\Http\Controllers\Student\StudentSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/extended', [StudentProfileController::class, 'updateExtended'])->name('profile.update-extended');

        // Academic Records
        Route::resource('academic-records', StudentAcademicRecordController::class);

        // Documents
        Route::resource('documents', StudentDocumentController::class);

        // Applications
        Route::resource('applications', StudentApplicationController::class)
            ->only(['index', 'show']);
        Route::patch('applications/{application}/cancel', [StudentApplicationController::class, 'cancel'])
            ->name('applications.cancel');

        // Scholarship Applications
        Route::resource('scholarship-applications', StudentScholarshipApplicationController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Cashbacks
        Route::resource('cashbacks', StudentCashbackController::class)
            ->only(['index', 'show']);

        // Favorites
        Route::resource('favorites', StudentFavoriteInstitutionController::class)
            ->only(['index', 'store', 'destroy']);

        // Recommendations
        Route::resource('recommendations', StudentRecommendationController::class)
            ->only(['index', 'show']);
        Route::patch('recommendations/{studentRecommendation}/mark-viewed', [StudentRecommendationController::class, 'markViewed'])
            ->name('recommendations.mark-viewed');

        // Counseling Sessions
        Route::resource('counseling-sessions', StudentCounselingSessionController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::patch('counseling-sessions/{counselingSession}/cancel', [StudentCounselingSessionController::class, 'cancel'])
            ->name('counseling-sessions.cancel');

        // Reviews
        Route::resource('reviews', StudentReviewController::class);

        // Conversations
        Route::resource('conversations', StudentConversationController::class)
            ->only(['index', 'create', 'store', 'show']);

        // Messages
        Route::resource('messages', StudentMessageController::class)
            ->only(['index', 'show', 'store', 'destroy']);
        Route::get('conversations/{conversation}/messages', [StudentMessageController::class, 'index'])
            ->name('conversations.messages.index');
        Route::post('conversations/{conversation}/messages', [StudentMessageController::class, 'store'])
            ->name('conversations.messages.store');

        // Settings
        Route::get('/settings', [StudentSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings/password', [StudentSettingController::class, 'updatePassword'])->name('settings.password.update');
        Route::delete('/settings/account', [StudentSettingController::class, 'destroy'])->name('settings.account.destroy');

        // Reward Claims
        Route::resource('reward-claims', \App\Http\Controllers\Student\StudentRewardClaimController::class)
            ->only(['index', 'create', 'store', 'show']);
    });
