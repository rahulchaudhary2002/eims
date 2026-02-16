<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionType;
use App\Models\Affiliation;
use App\Models\Program;
use App\Models\InstitutionCategory;
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
        $institutions = Institution::with(['affiliations', 'programs', 'institutionType'])->paginate(10);
        return view('admin.modules.institution.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $affiliations = Affiliation::active()->get();
        $programs = Program::active()->get();
        $institutionTypes = InstitutionType::orderBy('name')->get();
        $institutionCategories = InstitutionCategory::orderBy('name')->get();
        return view('admin.modules.institution.create', compact('affiliations', 'programs', 'institutionTypes', 'institutionCategories'));
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
            'type' => 'required|exists:institution_types,id',
            'affiliations' => 'nullable|array',
            'affiliations.*' => 'exists:affiliations,id',
            'programs' => 'nullable|array',
            'programs.*' => 'exists:programs,id',
            'commissions' => 'nullable|array',
            'commissions.*' => 'nullable|numeric',
            'institution_category' => 'required|exists:institution_categories,id',
            'is_active' => 'boolean',
        ]);

        $institution = Institution::create([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'established_year' => $validated['established_year'] ?? null,
            'institution_type_id' => $validated['type'],
            'institution_category_id' => $validated['institution_category'],
            'is_active' => $request->is_active ?? false
        ]);

        // Sync affiliations if provided
        if ($request->has('affiliations')) {
            $institution->affiliations()->sync($request->affiliations);
        }

        // Sync programs if provided
        if ($request->has('programs')) {
            $syncData = [];

            foreach ($request->programs as $index => $programId) {
                $syncData[$programId] = [
                    'commission_amount' => $request->commissions[$index] ?? 0
                ];
            }

            $institution->programs()->sync($syncData);
        }

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Institution $institution): View
    {
        $institution->load(['affiliations', 'programs', 'institutionType', 'category']);
        return view('admin.modules.institution.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institution $institution): View
    {
        $affiliations = Affiliation::active()->get();
        $programs = Program::active()->get();
        $institutionTypes = InstitutionType::orderBy('name')->get();
        $institutionCategories = InstitutionCategory::orderBy('name')->get();
        $institution->load(['affiliations', 'programs', 'institutionType', 'category']);

        return view('admin.modules.institution.edit', compact('institution', 'affiliations', 'programs', 'institutionTypes', 'institutionCategories'));
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
            'type' => 'required|exists:institution_types,id',
            'affiliations' => 'nullable|array',
            'affiliations.*' => 'exists:affiliations,id',
            'programs' => 'nullable|array',
            'programs.*' => 'exists:programs,id',
            'institution_category' => 'required|exists:institution_categories,id',
            'is_active' => 'boolean',
        ]);

        $institution->update([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'established_year' => $validated['established_year'] ?? null,
            'institution_type_id' => $validated['type'],
            'institution_category_id' => $validated['institution_category'],
            'is_active' => $request->is_active ?? false
        ]);

        // Sync affiliations
        if ($request->has('affiliations')) {
            $institution->affiliations()->sync($request->affiliations);
        } else {
            $institution->affiliations()->detach();
        }

        // Sync programs
        if ($request->has('programs')) {
            $syncData = [];

            foreach ($request->programs as $index => $programId) {
                $syncData[$programId] = [
                    'commission_amount' => $request->commissions[$index] ?? 0
                ];
            }

            $institution->programs()->sync($syncData);
        } else {
            $institution->programs()->detach();
        }

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institution $institution): RedirectResponse
    {
        // Detach all relations before deleting
        $institution->affiliations()->detach();
        $institution->programs()->detach();
        $institution->delete();

        return redirect()->route('admin.institution.index')
            ->with('success', 'Institution deleted successfully.');
    }
}
