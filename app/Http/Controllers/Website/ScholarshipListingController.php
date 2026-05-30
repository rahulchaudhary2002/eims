<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Program;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with(['institution', 'institutionProgram.program']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($institution = $request->input('institution')) {
            $query->where('institution_id', $institution);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($benefitType = $request->input('benefit_type')) {
            $query->where('benefit_type', $benefitType);
        }

        if ($minGpa = $request->input('min_gpa')) {
            $query->where('minimum_gpa', '<=', $minGpa);
        }

        $scholarships = $query->orderBy('end_date')
            ->paginate(12)
            ->withQueryString();

        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);
        $types        = Scholarship::TYPES;
        $benefitTypes = Scholarship::BENEFIT_TYPES;

        return view('website.scholarships.index', compact('scholarships', 'institutions', 'types', 'benefitTypes'));
    }

    public function show(Scholarship $scholarship)
    {
        abort_unless($scholarship->status === 'active', 404);

        $scholarship->load(['institution', 'institutionProgram.program.faculty']);

        $related = Scholarship::where('status', 'active')
            ->where('id', '!=', $scholarship->id)
            ->where('institution_id', $scholarship->institution_id)
            ->limit(3)
            ->get();

        return view('website.scholarships.show', compact('scholarship', 'related'));
    }
}
