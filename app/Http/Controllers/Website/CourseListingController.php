<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionCourse;
use Illuminate\Http\Request;

class CourseListingController extends Controller
{
    public function index(Request $request)
    {
        $query = InstitutionCourse::with('institution')
            ->where('is_active', true)
            ->whereHas('institution', fn($q) => $q->active());

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($institution = $request->input('institution')) {
            $query->where('institution_id', $institution);
        }

        if ($request->filled('fee_max')) {
            $query->where('fee', '<=', $request->input('fee_max'));
        }

        $courses = $query->orderBy('title')->paginate(12)->withQueryString();

        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);

        return view('website.courses.index', compact('courses', 'institutions'));
    }
}
