<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InstitutionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $institutionCategories = InstitutionCategory::withCount('institutions')
            ->latest()
            ->paginate(10);

        return view('admin.modules.institution-category.index', compact('institutionCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.modules.institution-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('name'));
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institution_categories,name',
            'slug' => 'required|string|max:255|unique:institution_categories,slug',
        ]);

        InstitutionCategory::create($validated);

        return redirect()->route('admin.institution-category.index')
            ->with('success', 'Institution category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InstitutionCategory $institutionCategory): View
    {
        return view('admin.modules.institution-category.edit', compact('institutionCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InstitutionCategory $institutionCategory): RedirectResponse
    {
        $slug = Str::slug($request->input('slug') ?: $request->input('name'));
        $request->merge(['slug' => $slug]);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institution_categories,name,' . $institutionCategory->id,
            'slug' => 'required|string|max:255|unique:institution_categories,slug,' . $institutionCategory->id,
        ]);

        $institutionCategory->update($validated);

        return redirect()->route('admin.institution-category.index')
            ->with('success', 'Institution category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InstitutionCategory $institutionCategory): RedirectResponse
    {
        if ($institutionCategory->institutions()->exists()) {
            return redirect()->route('admin.institution-category.index')
                ->with('error', 'This institution category is in use and cannot be deleted.');
        }

        $institutionCategory->delete();

        return redirect()->route('admin.institution-category.index')
            ->with('success', 'Institution category deleted successfully.');
    }
}
