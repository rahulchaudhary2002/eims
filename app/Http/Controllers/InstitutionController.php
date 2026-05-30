<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $institutionTypes = Institution::query()
            ->select('type', DB::raw('count(*) as institutions_count'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderBy('type')
            ->get()
            ->map(fn ($row) => (object) [
                'slug' => $row->type,
                'name' => Institution::TYPES[$row->type] ?? Str::headline($row->type),
                'institutions_count' => $row->institutions_count,
            ]);

        $typeSlugs = (array) $request->input('institutionTypes', []);
        $locations = (array) $request->input('locations', []);

        $institutions = Institution::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('institution_type'), function ($q) use ($request) {
                $q->where('type', $request->institution_type);
            })
            ->when(count($typeSlugs), function ($q) use ($typeSlugs) {
                $q->whereIn('type', $typeSlugs);
            })
            ->when(count($locations), function ($q) use ($locations) {
                $q->whereIn('location_slug', $locations);
            })
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
            'institutionTypes'
        ));
    }


    public function show($institution_slug)
    {
        $institution = Institution::where('slug', $institution_slug)->firstOrFail();

        return view('modules.institution.show', compact('institution'));
    }

    public function query($institution_slug, Request $request)
    {
        $institution = Institution::where('slug', $institution_slug)->firstOrFail();

        return view('modules.institution.query', compact('institution'));
    }

    public function storeQuery($institution_slug, Request $request)
    {
        $institution = Institution::where('slug', $institution_slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|max:100',
            'message' => 'required|string|max:2000',
        ]);

        Inquiry::create([
            'institution_id' => $institution->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => '[' . ucfirst($validated['type']) . '] ' . $validated['message'],
            'source' => 'website',
            'status' => 'new',
        ]);

        return redirect()->route('institution.query', ['institution_slug' => $institution_slug])
            ->with('success', 'Your question has been submitted successfully.');
    }

}
