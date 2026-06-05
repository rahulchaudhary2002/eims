<?php

use App\Http\Controllers\Website\ApplicationController;
use App\Http\Controllers\Website\CertificationListingController;
use App\Http\Controllers\Website\CollegeListingController;
use App\Http\Controllers\Website\CompareController;
use App\Http\Controllers\Website\ConsultancyListingController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\CourseListingController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\InquiryController;
use App\Http\Controllers\Website\InstitutionDetailController;
use App\Http\Controllers\Website\InstitutionListingController;
use App\Http\Controllers\Website\PostListingController;
use App\Http\Controllers\Website\ProgramListingController;
use App\Http\Controllers\Website\ScholarshipListingController;
use App\Http\Controllers\Website\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::name('website.')->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Institutions (all types)
    Route::get('/institutions', [InstitutionListingController::class, 'index'])->name('institutions.index');

    // Colleges (colleges only)
    Route::get('/colleges', [CollegeListingController::class, 'index'])->name('colleges.index');
    Route::get('/colleges/{institution:slug}', [InstitutionDetailController::class, 'show'])->name('colleges.show');
    Route::get('/colleges/{institution:slug}/programs', [InstitutionDetailController::class, 'programs'])->name('colleges.programs');
    Route::get('/colleges/{institution:slug}/programs/{institutionProgram:slug}', [InstitutionDetailController::class, 'programDetail'])->name('colleges.programs.show')->withoutScopedBindings();

    // Institutions (all types)
    Route::get('/institutions/{institution:slug}', [InstitutionDetailController::class, 'show'])->name('institutions.show');
    Route::get('/institutions/{institution:slug}/programs', [InstitutionDetailController::class, 'programs'])->name('institutions.programs');
    Route::get('/institutions/{institution:slug}/programs/{institutionProgram:slug}', [InstitutionDetailController::class, 'programDetail'])->name('institutions.programs.show')->withoutScopedBindings();

    // Programs
    Route::get('/programs', [ProgramListingController::class, 'index'])->name('programs.index');
    Route::get('/programs/{program:slug}', [ProgramListingController::class, 'show'])->name('programs.show');

    // Courses
    Route::get('/courses', [CourseListingController::class, 'index'])->name('courses.index');

    // Certifications
    Route::get('/certifications', [CertificationListingController::class, 'index'])->name('certifications.index');

    // Scholarships
    Route::get('/scholarships', [ScholarshipListingController::class, 'index'])->name('scholarships.index');
    Route::get('/scholarships/{scholarship:slug}', [ScholarshipListingController::class, 'show'])->name('scholarships.show');

    // Consultancies
    Route::get('/consultancies', [ConsultancyListingController::class, 'index'])->name('consultancies.index');
    Route::get('/consultancies/{institution:slug}', [ConsultancyListingController::class, 'show'])->name('consultancies.show');

    // Posts / Blog
    Route::get('/posts', [PostListingController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post:slug}', [PostListingController::class, 'show'])->name('posts.show');

    // Compare
    Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
    Route::post('/compare', [CompareController::class, 'store'])->name('compare.store');
    Route::delete('/compare/item/{id}', [CompareController::class, 'destroyItem'])->name('compare.destroy-item');
    Route::delete('/compare/{type}/{slug}', [CompareController::class, 'destroy'])->name('compare.destroy');

    // Inquiry
    Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry.create');
    Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store')->middleware('throttle:10,1');

    // Apply
    Route::get('/apply', [ApplicationController::class, 'create'])->name('applications.create')->middleware('auth:student');
    Route::post('/apply', [ApplicationController::class, 'store'])->name('applications.store')->middleware('auth:student');

    // Student actions (require student login)
    Route::middleware('auth:student')->group(function () {
        Route::post('/institutions/{institution:slug}/favorite', [InstitutionDetailController::class, 'toggleFavorite'])->name('institutions.favorite');
        Route::post('/institutions/{institution:slug}/review', [InstitutionDetailController::class, 'storeReview'])->name('institutions.review');
        Route::post('/colleges/{institution:slug}/favorite', [InstitutionDetailController::class, 'toggleFavorite'])->name('colleges.favorite');
        Route::post('/colleges/{institution:slug}/review', [InstitutionDetailController::class, 'storeReview'])->name('colleges.review');
    });

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');

    // Static Pages
    Route::get('/about', [StaticPageController::class, 'about'])->name('about');
    Route::get('/privacy-policy', [StaticPageController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/terms-and-conditions', [StaticPageController::class, 'terms'])->name('terms');
});
