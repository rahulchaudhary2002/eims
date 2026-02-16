<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affiliation;
use App\Models\Level;
use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $programs = Program::with(['level', 'affiliation', 'category'])->latest()->paginate(10);

        return view('admin.modules.program.index', compact('programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();
        $categories = ProgramCategory::all();

        return view('admin.modules.program.create', compact('levels', 'affiliations', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
            'code' => 'nullable|string|max:50|unique:programs,code',
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'category_id' => 'required|exists:program_categories,id',
            'fee' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Program::create([...$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Program created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program): View
    {
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();
        $categories = ProgramCategory::all();

        return view('admin.modules.program.edit', compact('program', 'levels', 'affiliations', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:programs,name,' . $program->id,
            'code' => 'nullable|string|max:50|unique:programs,code,' . $program->id,
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'category_id' => 'required|exists:program_categories,id',
            'fee' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $program->update([...$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.program.index')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.program.index')
            ->with('success', 'Program deleted successfully.');
    }
}
