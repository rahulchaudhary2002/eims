<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AffiliationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $affiliations = Affiliation::latest()->paginate(10);

        return view('admin.modules.affiliation.index', compact('affiliations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.modules.affiliation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:affiliations,name',
            'code' => 'nullable|string|max:50|unique:affiliations,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Affiliation::create([$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.affiliation.index')
            ->with('success', 'Affiliation created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Affiliation $affiliation): View
    {
        return view('admin.modules.affiliation.edit', compact('affiliation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Affiliation $affiliation): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:affiliations,name,' . $affiliation->id,
            'code' => 'nullable|string|max:50|unique:affiliations,code,' . $affiliation->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $affiliation->update([$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.affiliation.index')
            ->with('success', 'Affiliation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Affiliation $affiliation): RedirectResponse
    {
        $affiliation->delete();

        return redirect()->route('admin.affiliation.index')
            ->with('success', 'Affiliation deleted successfully.');
    }
}
