<?php

use App\Http\Controllers\Institution\InstitutionAdmissionController;
use App\Http\Controllers\Institution\InstitutionCertificationController;
use App\Http\Controllers\Institution\InstitutionConsultancyDestinationController;
use App\Http\Controllers\Institution\InstitutionConsultancyServiceController;
use App\Http\Controllers\Institution\InstitutionCourseController;
use App\Http\Controllers\Institution\InstitutionApplicationController;
use App\Http\Controllers\Institution\InstitutionCommissionInvoiceController;
use App\Http\Controllers\Institution\InstitutionCommissionPaymentController;
use App\Http\Controllers\Institution\InstitutionConversationController;
use App\Http\Controllers\Institution\InstitutionCounselingSessionController;
use App\Http\Controllers\Institution\InstitutionDashboardController;
use App\Http\Controllers\Institution\InstitutionDocumentController;
use App\Http\Controllers\Institution\InstitutionInquiryController;
use App\Http\Controllers\Institution\InstitutionLeadFollowUpController;
use App\Http\Controllers\Institution\InstitutionLeadNoteController;
use App\Http\Controllers\Institution\InstitutionMessageController;
use App\Http\Controllers\Institution\InstitutionPostController;
use App\Http\Controllers\Institution\InstitutionPostMediaController;
use App\Http\Controllers\Institution\InstitutionProfileController;
use App\Http\Controllers\Institution\InstitutionProgramController;
use App\Http\Controllers\Institution\InstitutionProgramSubjectController;
use App\Http\Controllers\Institution\InstitutionPromotionController;
use App\Http\Controllers\Institution\InstitutionReferralController;
use App\Http\Controllers\Institution\InstitutionReviewController;
use App\Http\Controllers\Institution\InstitutionScholarshipController;
use App\Http\Controllers\Institution\InstitutionSubscriptionController;
use App\Http\Controllers\Institution\InstitutionSwitcherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'institution.user'])
    ->prefix('institution')
    ->name('institution.')
    ->group(function () {
        Route::get('/select', [InstitutionSwitcherController::class, 'index'])->name('select');
        Route::post('/select', [InstitutionSwitcherController::class, 'store'])->name('select.store');

        Route::middleware(['active.institution'])->group(function () {
            Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
            Route::get('/institutions', [InstitutionProfileController::class, 'institutions'])->name('institutions.index');

            Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
            Route::put('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');

            Route::resource('documents', InstitutionDocumentController::class);
            Route::resource('programs', InstitutionProgramController::class);
            Route::resource('program-subjects', InstitutionProgramSubjectController::class);
            Route::resource('courses', InstitutionCourseController::class);
            Route::resource('certifications', InstitutionCertificationController::class);
            Route::resource('consultancy-destinations', InstitutionConsultancyDestinationController::class);
            Route::resource('consultancy-services', InstitutionConsultancyServiceController::class);
            Route::resource('scholarships', InstitutionScholarshipController::class);
            Route::resource('applications', InstitutionApplicationController::class)->only(['index', 'show', 'update']);
            Route::resource('admissions', InstitutionAdmissionController::class)->only(['index', 'show', 'update']);
            Route::resource('inquiries', InstitutionInquiryController::class);
            Route::resource('lead-notes', InstitutionLeadNoteController::class);
            Route::resource('lead-follow-ups', InstitutionLeadFollowUpController::class);
            Route::resource('counseling-sessions', InstitutionCounselingSessionController::class)->only(['index', 'show', 'edit', 'update'])->whereNumber('counseling_session');
            Route::patch('counseling-sessions/{counseling_session}/approve', [InstitutionCounselingSessionController::class, 'approve'])->name('counseling-sessions.approve');
            Route::resource('posts', InstitutionPostController::class);
            Route::resource('post-media', InstitutionPostMediaController::class);
            Route::resource('reviews', InstitutionReviewController::class)->only(['index', 'show']);
            Route::resource('conversations', InstitutionConversationController::class)->only(['index', 'show']);
            Route::post('conversations/{conversation}/messages', [InstitutionConversationController::class, 'storeMessage'])
                ->name('conversations.messages.store');
            Route::delete('messages/{message}', [InstitutionMessageController::class, 'destroy'])
                ->name('messages.destroy');
            Route::resource('promotions', InstitutionPromotionController::class);
            Route::resource('subscriptions', InstitutionSubscriptionController::class)->only(['index', 'show']);
            Route::resource('referrals', InstitutionReferralController::class)->only(['index', 'show']);
            Route::post('referrals/{referral}/request-unlock', [InstitutionReferralController::class, 'requestUnlock'])
                ->name('referrals.request-unlock');
            Route::patch('referrals/{referral}/accept', [InstitutionReferralController::class, 'accept'])
                ->name('referrals.accept');
            Route::patch('referrals/{referral}/reject', [InstitutionReferralController::class, 'reject'])
                ->name('referrals.reject');
            Route::patch('referrals/{referral}/request-more-info', [InstitutionReferralController::class, 'requestMoreInfo'])
                ->name('referrals.request-more-info');
            Route::resource('commission-invoices', InstitutionCommissionInvoiceController::class)->only(['index', 'show']);
            Route::resource('commission-payments', InstitutionCommissionPaymentController::class);

        });
    });
