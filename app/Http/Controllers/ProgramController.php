<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::active()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->filled('category'), fn($q) => $q->whereHas('faculty', fn($qq) => $qq->where('slug', $request->category)))
            ->when($request->filled('level'), fn($q) => $q->where('level', $request->level))
            ->with('faculty')
            ->withCount([
                'courses as active_courses_count' => fn($q) => $q->where('is_active', true),
            ])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = Faculty::whereHas('programs', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $levels = Program::active()
            ->distinct()
            ->orderBy('level')
            ->pluck('level')
            ->filter()
            ->map(fn($l) => (object) ['slug' => $l, 'name' => ucfirst($l)]);

        return view('modules.program.index', compact('programs', 'categories', 'levels'));
    }

    public function show(Program $program)
    {
        $program->load([
            'faculty',
            'courses' => fn($q) => $q->where('is_active', true),
        ]);

        $relatedPrograms = Program::active()
            ->where('id', '!=', $program->id)
            ->when($program->faculty_id, fn($q) => $q->where('faculty_id', $program->faculty_id))
            ->with('faculty')
            ->limit(6)
            ->get();

        return view('modules.program.show', compact('program', 'relatedPrograms'));
    }
}
