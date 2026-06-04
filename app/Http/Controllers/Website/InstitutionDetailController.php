<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\InstitutionReview;
use App\Models\StudentCompareItem;
use App\Models\StudentFavoriteInstitution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstitutionDetailController extends Controller
{
    private function routePrefix(): string
    {
        return request()->routeIs('website.colleges.*') ? 'website.colleges' : 'website.institutions';
    }

    public function show(Institution $institution): View
    {
        abort_unless($institution->status === 'active', 404);

        $routePrefix = $this->routePrefix();

        $institution->load([
            'profile',
            'documents'  => fn($q) => $q->where('status', 'active'),
            'programs'   => fn($q) => $q->whereIn('status', ['open', 'upcoming'])->with('program.faculty')->orderBy('status'),
            'scholarships' => fn($q) => $q->where('status', 'active')->whereDate('end_date', '>=', now()),
            'posts'      => fn($q) => $q->where('is_published', true)->with('media')->orderByDesc('published_at')->limit(4),
            'reviews'    => fn($q) => $q->where('is_approved', true)->with('student')->orderByDesc('created_at')->limit(10),
            'consultancyServices'     => fn($q) => $q->where('is_active', true),
            'consultancyDestinations' => fn($q) => $q->where('is_active', true),
        ]);

        $institution->loadCount([
            'reviews' => fn($q) => $q->where('is_approved', true),
            'followers',
        ]);

        $institution->loadAvg(['reviews' => fn($q) => $q->where('is_approved', true)], 'rating');

        $isFavorited  = false;
        $compareItems = [];

        if (Auth::guard('student')->check()) {
            $student     = Auth::guard('student')->user();
            $isFavorited = StudentFavoriteInstitution::where('student_id', $student->id)
                ->where('institution_id', $institution->id)
                ->exists();

            $compareItems = StudentCompareItem::where('student_id', $student->id)
                ->pluck('institution_id')
                ->toArray();
        } else {
            $compareItems = session('website_compare', []);
        }

        return view('website.institutions.show', compact('institution', 'isFavorited', 'compareItems', 'routePrefix'));
    }

    public function programs(Institution $institution): View
    {
        abort_unless($institution->status === 'active', 404);

        $routePrefix = $this->routePrefix();

        $programs = $institution->programs()
            ->with('program.faculty')
            ->where('status', '!=', 'suspended')
            ->orderBy('status')
            ->paginate(12)
            ->withQueryString();

        return view('website.institutions.programs', compact('institution', 'programs', 'routePrefix'));
    }

    public function programDetail(Institution $institution, InstitutionProgram $institutionProgram): View
    {
        abort_unless($institution->status === 'active', 404);
        abort_unless($institutionProgram->institution_id === $institution->id, 404);

        $routePrefix = $this->routePrefix();

        $institutionProgram->load(['program.faculty', 'subjects', 'scholarships' => fn($q) => $q->where('status', 'active')]);

        return view('website.institutions.program-detail', compact('institution', 'institutionProgram', 'routePrefix'));
    }

    public function toggleFavorite(Institution $institution): RedirectResponse
    {
        $student  = Auth::guard('student')->user();
        $existing = StudentFavoriteInstitution::where('student_id', $student->id)
            ->where('institution_id', $institution->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from favorites.';
        } else {
            StudentFavoriteInstitution::create([
                'student_id'     => $student->id,
                'institution_id' => $institution->id,
            ]);
            $message = 'Added to favorites.';
        }

        return back()->with('success', $message);
    }

    public function storeReview(Request $request, Institution $institution): RedirectResponse
    {
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string', 'max:2000'],
        ]);

        $alreadyReviewed = InstitutionReview::where('student_id', $student->id)
            ->where('institution_id', $institution->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already submitted a review for this institution.');
        }

        InstitutionReview::create([
            'student_id'     => $student->id,
            'institution_id' => $institution->id,
            'rating'         => $validated['rating'],
            'review'         => $validated['review'],
            'is_approved'    => false,
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval.');
    }
}
