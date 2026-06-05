<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Institution;
use App\Models\InstitutionCertification;
use App\Models\InstitutionCourse;
use App\Models\InstitutionProgram;
use App\Models\InstitutionReview;
use App\Models\Post;
use App\Models\Scholarship;

class HomeController extends Controller
{
    public function index()
    {
        $featuredInstitutions = Institution::active()
            ->where('is_featured', true)
            ->with(['profile', 'programs'])
            ->withCount(['programs', 'reviews'])
            ->withAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $openPrograms = InstitutionProgram::where('status', 'open')
            ->with(['institution', 'program.faculty'])
            ->whereHas('institution', fn($q) => $q->active())
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $activeScholarships = Scholarship::where('status', 'active')
            ->with('institution', 'institutionProgram.program')
            ->whereDate('end_date', '>=', now())
            ->orderBy('end_date')
            ->limit(4)
            ->get();

        $consultancies = Institution::active()
            ->where('type', 'consultancy')
            ->with(['consultancyServices' => fn($q) => $q->where('is_active', true)->limit(3)])
            ->withCount(['consultancyDestinations' => fn($q) => $q->where('is_active', true)])
            ->limit(4)
            ->get();

        $featuredCourses = InstitutionCourse::where('is_active', true)
            ->with('institution')
            ->whereHas('institution', fn($q) => $q->active())
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $featuredCertifications = InstitutionCertification::where('is_active', true)
            ->with('institution')
            ->whereHas('institution', fn($q) => $q->active())
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $latestPosts = Post::where('is_published', true)
            ->with('institution')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        $recentReviews = InstitutionReview::where('is_approved', true)
            ->with(['student', 'institution'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $stats = [
            'institutions' => Institution::active()->count(),
            'programs'     => InstitutionProgram::where('status', 'open')->count(),
            'scholarships' => Scholarship::where('status', 'active')->count(),
            'faculties'    => Faculty::where('is_active', true)->count(),
        ];

        $faculties    = Faculty::where('is_active', true)->orderBy('name')->get();
        $provinces    = Institution::active()->whereNotNull('province')->distinct()->pluck('province');

        return view('website.home', compact(
            'featuredInstitutions',
            'openPrograms',
            'featuredCourses',
            'featuredCertifications',
            'activeScholarships',
            'consultancies',
            'latestPosts',
            'recentReviews',
            'stats',
            'faculties',
            'provinces'
        ));
    }
}
