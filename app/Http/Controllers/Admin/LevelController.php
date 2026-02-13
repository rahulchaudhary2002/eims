<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $levels = Level::ordered()->paginate(10);

        return view('admin.modules.level.index', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.modules.level.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name',
            'code' => 'nullable|string|max:20|unique:levels,code',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Level::create([...$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.level.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level): View
    {
        return view('admin.modules.level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels,name,' . $level->id,
            'code' => 'nullable|string|max:20|unique:levels,code,' . $level->id,
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $level->update([...$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.level.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level): RedirectResponse
    {
        $level->delete();

        return redirect()->route('admin.level.index')
            ->with('success', 'Level deleted successfully.');
    }
}
