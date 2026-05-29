<?php

use App\Http\Controllers\Admin\AdmissionController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\ApplicationStatusLogController;
use App\Http\Controllers\Admin\AffiliationController;
use App\Http\Controllers\Admin\CommissionInvoiceController;
use App\Http\Controllers\Admin\CommissionPaymentController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\LeadFollowUpController;
use App\Http\Controllers\Admin\LeadNoteController;
use App\Http\Controllers\Admin\StudentFavoriteInstitutionController;
use App\Http\Controllers\Admin\ScholarshipApplicationController;
use App\Http\Controllers\Admin\ScholarshipCashbackController;
use App\Http\Controllers\Admin\ReferralAgreementController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\BulkModuleImportController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CurrentInstitutionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\InstitutionCategoryController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\InstitutionDocumentController;
use App\Http\Controllers\Admin\InstitutionProfileController;
use App\Http\Controllers\Admin\StudentAcademicRecordController;
use App\Http\Controllers\Admin\StudentDocumentController;
use App\Http\Controllers\Admin\StudentProfileController;
use App\Http\Controllers\Admin\InstitutionProgramController;
use App\Http\Controllers\Admin\InstitutionProgramSubjectController;
use App\Http\Controllers\Admin\InstitutionTypeController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ScholarshipController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth:web'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth:web', 'verified:admin'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

    Route::resource('students', StudentController::class);
    Route::patch('students/{student}/status', [StudentController::class, 'updateStatus'])->name('students.update-status');

    Route::resource('institutions', InstitutionController::class);
    Route::patch('institutions/{institution}/status', [InstitutionController::class, 'updateStatus'])->name('institutions.update-status');
    Route::post('switch-institution', [CurrentInstitutionController::class, 'switch'])->name('institutions.switch-current');

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

    Route::resource('institution-type', InstitutionTypeController::class)->except('show');
    Route::resource('institution-category', InstitutionCategoryController::class)->except('show');
    Route::resource('vendor', VendorController::class);

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

    Route::resource('affiliation', AffiliationController::class)->except('show');
    Route::resource('level', LevelController::class)->except('show');
    Route::resource('programs', ProgramController::class);
    Route::patch('programs/{program}/status', [ProgramController::class, 'updateStatus'])
        ->name('programs.update-status');
    Route::resource('course', CourseController::class);
    Route::get('bulk-import', [BulkModuleImportController::class, 'index'])->name('bulk-import.index');
    Route::post('bulk-import', [BulkModuleImportController::class, 'store'])->name('bulk-import.store');
    Route::get('bulk-import/template', [BulkModuleImportController::class, 'template'])->name('bulk-import.template');

    Route::post('notification/read-all', [SettingController::class, 'readAllNotifications'])
        ->name('notification.read-all');
});
