<?php

use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\ApplicationStatusLogController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ConsultancyDestinationController;
use App\Http\Controllers\Admin\ConsultancyServiceController;
use App\Http\Controllers\Admin\InstitutionReviewController;
use App\Http\Controllers\Admin\InstitutionSubscriptionController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\CounselingSessionController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\InstitutionFollowerController;
use App\Http\Controllers\Admin\CommissionInvoiceController;
use App\Http\Controllers\Admin\CommissionPaymentController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\LeadFollowUpController;
use App\Http\Controllers\Admin\LeadNoteController;
use App\Http\Controllers\Admin\PostCommentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostMediaController;
use App\Http\Controllers\Admin\PostReactionController;
use App\Http\Controllers\Admin\StudentCompareItemController;
use App\Http\Controllers\Admin\StudentFavoriteInstitutionController;
use App\Http\Controllers\Admin\StudentRecommendationController;
use App\Http\Controllers\Admin\ScholarshipApplicationController;
use App\Http\Controllers\Admin\ScholarshipCashbackController;
use App\Http\Controllers\Admin\ReferralAgreementController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\BulkModuleImportController;
use App\Http\Controllers\Admin\CurrentInstitutionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\InstitutionDocumentController;
use App\Http\Controllers\Admin\InstitutionProfileController;
use App\Http\Controllers\Admin\StudentAcademicRecordController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\InstitutionProgramController;
use App\Http\Controllers\Admin\InstitutionProgramSubjectController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'admin.user'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth:web', 'verified:admin'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

    Route::resource('students', StudentController::class);
    Route::patch('students/{student}/status', [StudentController::class, 'updateStatus'])->name('students.update-status');

    Route::resource('institutions', InstitutionController::class);
    Route::patch('institutions/{institution}/status', [InstitutionController::class, 'updateStatus'])->name('institutions.update-status');
    Route::post('switch-institution', [CurrentInstitutionController::class, 'switch'])->name('institutions.switch-current');

    Route::resource('institution-followers', InstitutionFollowerController::class)->only(['index', 'show', 'destroy']);

    Route::resource('institution-reviews', InstitutionReviewController::class);
    Route::patch('institution-reviews/{institutionReview}/approve', [InstitutionReviewController::class, 'approve'])
        ->name('institution-reviews.approve');

    Route::resource('consultancy-destinations', ConsultancyDestinationController::class);
    Route::patch('consultancy-destinations/{consultancyDestination}/status', [ConsultancyDestinationController::class, 'updateStatus'])
        ->name('consultancy-destinations.update-status');

    Route::resource('consultancy-services', ConsultancyServiceController::class);
    Route::patch('consultancy-services/{consultancyService}/status', [ConsultancyServiceController::class, 'updateStatus'])
        ->name('consultancy-services.update-status');

    Route::resource('counseling-sessions', CounselingSessionController::class);
    Route::patch('counseling-sessions/{counselingSession}/status', [CounselingSessionController::class, 'updateStatus'])
        ->name('counseling-sessions.update-status');

    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::patch('subscription-plans/{subscriptionPlan}/status', [SubscriptionPlanController::class, 'updateStatus'])
        ->name('subscription-plans.update-status');

    Route::resource('institution-subscriptions', InstitutionSubscriptionController::class);
    Route::patch('institution-subscriptions/{institutionSubscription}/status', [InstitutionSubscriptionController::class, 'updateStatus'])
        ->name('institution-subscriptions.update-status');

    Route::resource('promotions', PromotionController::class);
    Route::patch('promotions/{promotion}/status', [PromotionController::class, 'updateStatus'])
        ->name('promotions.update-status');

    Route::resource('institution-profiles', InstitutionProfileController::class);

    Route::resource('institution-documents', InstitutionDocumentController::class);
    Route::patch('institution-documents/{institutionDocument}/status', [InstitutionDocumentController::class, 'updateStatus'])
        ->name('institution-documents.update-status');

    Route::resource('institution-programs', InstitutionProgramController::class);
    Route::patch('institution-programs/{institutionProgram}/status', [InstitutionProgramController::class, 'updateStatus'])
        ->name('institution-programs.update-status');

    Route::resource('institution-program-subjects', InstitutionProgramSubjectController::class);

    Route::resource('student-profiles', StudentProfileController::class);

    Route::resource('student-academic-records', StudentAcademicRecordController::class);
    Route::patch('student-academic-records/{studentAcademicRecord}/verify', [StudentAcademicRecordController::class, 'verify'])
        ->name('student-academic-records.verify');

    Route::resource('student-documents', StudentDocumentController::class);
    Route::patch('student-documents/{studentDocument}/status', [StudentDocumentController::class, 'updateStatus'])
        ->name('student-documents.update-status');

    Route::resource('faculties', FacultyController::class);
    Route::patch('faculties/{faculty}/status', [FacultyController::class, 'updateStatus'])
        ->name('faculties.update-status');

    Route::resource('scholarships', ScholarshipController::class);
    Route::patch('scholarships/{scholarship}/status', [ScholarshipController::class, 'updateStatus'])
        ->name('scholarships.update-status');

    Route::resource('applications', ApplicationController::class);
    Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])
        ->name('applications.update-status');

    Route::resource('application-status-logs', ApplicationStatusLogController::class)
        ->only(['index', 'show', 'destroy']);

    Route::resource('admissions', AdmissionController::class);
    Route::patch('admissions/{admission}/verify', [AdmissionController::class, 'verify'])
        ->name('admissions.verify');

    Route::resource('referral-agreements', ReferralAgreementController::class);
    Route::patch('referral-agreements/{referralAgreement}/status', [ReferralAgreementController::class, 'updateStatus'])
        ->name('referral-agreements.update-status');

    Route::resource('referrals', ReferralController::class);
    Route::patch('referrals/{referral}/status', [ReferralController::class, 'updateStatus'])
        ->name('referrals.update-status');

    Route::resource('commission-invoices', CommissionInvoiceController::class);
    Route::patch('commission-invoices/{commissionInvoice}/status', [CommissionInvoiceController::class, 'updateStatus'])
        ->name('commission-invoices.update-status');

    Route::resource('commission-payments', CommissionPaymentController::class);

    Route::resource('scholarship-applications', ScholarshipApplicationController::class);
    Route::patch('scholarship-applications/{scholarshipApplication}/status', [ScholarshipApplicationController::class, 'updateStatus'])
        ->name('scholarship-applications.update-status');

    Route::resource('scholarship-cashbacks', ScholarshipCashbackController::class);
    Route::patch('scholarship-cashbacks/{scholarshipCashback}/status', [ScholarshipCashbackController::class, 'updateStatus'])
        ->name('scholarship-cashbacks.update-status');

    Route::resource('inquiries', InquiryController::class);
    Route::patch('inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])
        ->name('inquiries.update-status');

    Route::resource('lead-notes', LeadNoteController::class);

    Route::resource('lead-follow-ups', LeadFollowUpController::class);
    Route::patch('lead-follow-ups/{leadFollowUp}/status', [LeadFollowUpController::class, 'updateStatus'])
        ->name('lead-follow-ups.update-status');

    Route::resource('student-favorite-institutions', StudentFavoriteInstitutionController::class);

    Route::resource('student-compare-items', StudentCompareItemController::class);

    Route::resource('student-recommendations', StudentRecommendationController::class);
    Route::patch('student-recommendations/{studentRecommendation}/mark-viewed', [StudentRecommendationController::class, 'markViewed'])
        ->name('student-recommendations.mark-viewed');

    Route::resource('posts', PostController::class);
    Route::patch('posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');
    Route::patch('posts/{post}/feature', [PostController::class, 'feature'])->name('posts.feature');

    Route::resource('post-media', PostMediaController::class);

    Route::resource('post-reactions', PostReactionController::class)->only(['index', 'show', 'destroy']);

    Route::resource('post-comments', PostCommentController::class);
    Route::patch('post-comments/{postComment}/toggle-hidden', [PostCommentController::class, 'toggleHidden'])
        ->name('post-comments.toggle-hidden');

    Route::resource('programs', ProgramController::class);
    Route::patch('programs/{program}/status', [ProgramController::class, 'updateStatus'])
        ->name('programs.update-status');
    Route::get('bulk-import', [BulkModuleImportController::class, 'index'])->name('bulk-import.index');
    Route::post('bulk-import', [BulkModuleImportController::class, 'store'])->name('bulk-import.store');
    Route::get('bulk-import/template', [BulkModuleImportController::class, 'template'])->name('bulk-import.template');

    Route::resource('conversations', ConversationController::class);
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])
        ->name('conversations.messages.store');

    Route::post('notification/read-all', [SettingController::class, 'readAllNotifications'])
        ->name('notification.read-all');
});
