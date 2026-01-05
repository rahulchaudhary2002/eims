<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $institutions = Institution::with('affiliations')->paginate(10);

        return view('admin.modules.institution.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $affiliations = Affiliation::active()->get();
        return view('admin.modules.institution.create', compact('affiliations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'established_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'type' => 'required|in:college,school',
            'affiliations' => 'nullable|array',
            'affiliations.*' => 'exists:affiliations,id',
            'is_active' => 'boolean',
        ]);

        $institution = Institution::create([$validated, 'is_active' => $request->is_active ?? false]);

        // Sync affiliations if provided
        if ($request->has('affiliations')) {
            $institution->affiliations()->sync($request->affiliations);
        }

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution): View
    {
        $institution->load('affiliations');
        return view('admin.modules.institution.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $institution): View
    {
        $affiliations = Affiliation::active()->get();
        $institution->load('affiliations');

        return view('admin.modules.institution.edit', compact('institution', 'affiliations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institution $institution): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'established_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'type' => 'required|in:college,school',
            'affiliations' => 'nullable|array',
            'affiliations.*' => 'exists:affiliations,id',
            'is_active' => 'boolean',
        ]);

        $institution->update([$validated, 'is_active' => $request->is_active ?? false]);

        // Sync affiliations
        if ($request->has('affiliations')) {
            $institution->affiliations()->sync($request->affiliations);
        } else {
            $institution->affiliations()->detach();
        }

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution): RedirectResponse
    {
        // Detach all affiliations before deleting
        $institution->affiliations()->detach();
        $institution->delete();

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution deleted successfully.');
    }
}
