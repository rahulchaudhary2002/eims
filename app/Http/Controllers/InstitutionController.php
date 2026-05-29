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

        $typeSlugs = (array) $request->input('institutionTypes', []);
        $affSlugs  = (array) $request->input('affiliatedUniversities', []);
        $catSlugs  = (array) $request->input('categories', []);
        $locations = (array) $request->input('locations', []);

        $institutions = Institution::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('institution_type'), function ($q) use ($request) {
                $q->whereHas('institutionType', fn($qq) => $qq->where('slug', $request->institution_type));
            })
            ->when($request->filled('affiliated_university'), function ($q) use ($request) {
                $q->whereHas('affiliations', fn($qq) => $qq->where('slug', $request->affiliated_university));
            })
            ->when($request->filled('institution_category'), function ($q) use ($request) {
                $q->whereHas('category', fn($qq) => $qq->where('slug', $request->institution_category));
            })
            ->when(count($typeSlugs), function ($q) use ($typeSlugs) {
                $q->whereHas('institutionType', fn($qq) => $qq->whereIn('slug', $typeSlugs));
            })
            ->when(count($affSlugs), function ($q) use ($affSlugs) {
                $q->whereHas('affiliations', fn($qq) => $qq->whereIn('slug', $affSlugs));
            })
            ->when(count($catSlugs), function ($q) use ($catSlugs) {
                $q->whereHas('category', fn($qq) => $qq->whereIn('slug', $catSlugs));
            })
            ->when(count($locations), function ($q) use ($locations) {
                $q->whereIn('location_slug', $locations);
            })
            ->with(['institutionType', 'affiliations', 'category'])
            ->withCount('programs')

            ->when($request->filled('sort'), function ($q) use ($request) {
                $allowed = ['name', 'established_year'];
                $sort = in_array($request->sort, $allowed) ? $request->sort : 'name';
                $q->orderBy($sort, 'asc');
            }, function ($q) {
                $q->orderBy('name', 'asc');
            })
            ->paginate(4)
            ->withQueryString();

        return view('modules.institution.index', compact(
            'institutions',
            'institutionTypes',
            'affiliations',
            'institutionCategories'
        ));
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

}
