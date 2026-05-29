<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProgramRequest;
use App\Http\Requests\Admin\UpdateProgramRequest;
use App\Models\Faculty;
use App\Models\Level;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $query = Program::with('faculty');

        if ($search = $request->input('search')) {
            $query->where('name', 'ilike', '%' . $search . '%');
        }
        if ($facultyId = $request->input('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }
        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $programs  = $query->orderBy('name')->paginate(20)->withQueryString();
        $faculties = Faculty::orderBy('name')->get(['id', 'name']);
        $levels    = Level::where('is_active', true)->orderBy('order')->orderBy('name')->pluck('name', 'name');

        return view('admin.programs.index', compact('programs', 'faculties', 'levels'));
    }

    public function create(): View
    {
        $faculties = Faculty::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $levels    = Level::where('is_active', true)->orderBy('order')->orderBy('name')->pluck('name', 'name');

        return view('admin.programs.create', compact('faculties', 'levels'));
    }

    public function store(StoreProgramRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $program = Program::create($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Program created successfully.');
    }

    public function show(Program $program): View
    {
        $program->load([
            'faculty',
            'institutionPrograms' => fn ($q) => $q->with('institution')->orderBy('created_at', 'desc'),
        ]);

        return view('admin.programs.show', compact('program'));
    }

    public function edit(Program $program): View
    {
        $program->load('faculty');
        $faculties = Faculty::where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $levels    = Level::where('is_active', true)->orderBy('order')->orderBy('name')->pluck('name', 'name');

        return view('admin.programs.edit', compact('program', 'faculties', 'levels'));
    }

    public function update(UpdateProgramRequest $request, Program $program): RedirectResponse
    {
        $data = $request->validated();
        $data['slug']      = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $program->update($data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program deleted successfully.');
    }

    public function updateStatus(Request $request, Program $program): RedirectResponse
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $program->update(['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Program status updated.');
    }
}
