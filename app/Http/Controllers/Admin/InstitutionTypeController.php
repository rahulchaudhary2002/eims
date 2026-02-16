<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstitutionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $institutionTypes = InstitutionType::withCount('institutions')
            ->latest()
            ->paginate(10);

        return view('admin.modules.institution-type.index', compact('institutionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.modules.institution-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('name'));
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institution_types,name',
            'slug' => 'required|string|max:255|unique:institution_types,slug',
        ]);

        InstitutionType::create($validated);

        return redirect()->route('admin.institution-type.index')
            ->with('success', 'Institution type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstitutionType $institutionType): View
    {
        return view('admin.modules.institution-type.edit', compact('institutionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstitutionType $institutionType): RedirectResponse
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('name'));
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institution_types,name,' . $institutionType->id,
            'slug' => 'required|string|max:255|unique:institution_types,slug,' . $institutionType->id,
        ]);

        $institutionType->update($validated);

        return redirect()->route('admin.institution-type.index')
            ->with('success', 'Institution type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstitutionType $institutionType): RedirectResponse
    {
        if ($institutionType->institutions()->exists()) {
            return redirect()->route('admin.institution-type.index')
                ->with('error', 'This institution type is in use and cannot be deleted.');
        }

        $institutionType->delete();

        return redirect()->route('admin.institution-type.index')
            ->with('success', 'Institution type deleted successfully.');
    }
}
