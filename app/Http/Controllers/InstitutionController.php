<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\Enquiry;
use App\Models\Institution;
use App\Models\InstitutionCategory;
use App\Models\InstitutionType;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $institutionTypes = InstitutionType::whereHas('institutions')
            ->withCount('institutions')
            ->latest()
            ->get();
        $affiliations = Affiliation::whereHas('institutions')
            ->withCount('institutions')
            ->latest()
            ->get();
        $institutionCategories = InstitutionCategory::whereHas('institutions')
            ->withCount('institutions')
            ->latest()
            ->get();
        $institutions = Institution::query()->when($request->college_name, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->input('college_name') . '%');
        })->when($request->institution_type, function ($query) use ($request) {
            $query->whereHas('institutionType', function ($q) use ($request) {
                $q->where('slug', $request->input('institution_type'));
            });
        })->when($request->affiliated_university, function ($query) use ($request) {
            $query->whereHas('affiliations', function ($q) use ($request) {
                $q->where('slug', $request->input('affiliated_university'));
            });
        })->when($request->institution_category, function ($query) use ($request) {
            $query->whereHas('institutionCategory', function ($q) use ($request) {
                $q->where('slug', $request->input('institution_category'));
            });
        })->with('institutionType')->withCount(['courses'])
            ->orderBy($request->input('sort', 'name'), 'asc')->paginate(4);

        return view('modules.institution.index', compact('institutions', 'institutionTypes', 'affiliations', 'institutionCategories'));
    }

    public function show($institution_slug)
    {
        $institution = Institution::with('institutionType')
            ->where('slug', $institution_slug)
            ->firstOrFail();

        return view('modules.institution.show', compact('institution'));
    }

    public function query($institution_slug, Request $request)
    {
        $institution = Institution::with('institutionType')
            ->where('slug', $institution_slug)
            ->firstOrFail();

        return view('modules.institution.query', compact('institution'));
    }

    public function storeQuery($institution_slug, Request $request)
    {
        $institution = Institution::with('institutionType')
            ->where('slug', $institution_slug)
            ->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        Enquiry::create([
            'institution_id' => $institution->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'type' => $validated['type'],
            'message' => $validated['message'],
        ]);

        return redirect()->route('institution.query', ['institution_slug' => $institution_slug])
            ->with('success', 'Your question has been submitted successfully.');
    }

    public function admissions($institution_slug)
    {
        $institution = Institution::with('institutionType')
            ->where('slug', $institution_slug)
            ->firstOrFail();

        $admissions = $institution->admissions()->latest()->paginate(12);

        return view('modules.institution.admission', compact('admissions', 'institution'));
    }
}
