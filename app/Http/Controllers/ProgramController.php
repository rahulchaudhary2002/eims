<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::active()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn($qq) => $qq->where('slug', $request->category));
            })
            ->when($request->filled('level'), function ($q) use ($request) {
                $q->whereHas('level', fn($qq) => $qq->where('slug', $request->level));
            })
            ->with(['category', 'level', 'affiliation'])
            ->withCount([
                'courses as active_courses_count' => fn($q) => $q->where('courses.is_active', true),
            ])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = \App\Models\ProgramCategory::query()
            ->whereHas('programs', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $levels = \App\Models\Level::active()
            ->whereHas('programs', fn($q) => $q->where('is_active', true))
            ->ordered()
            ->get();

        return view('modules.program.index', compact('programs', 'categories', 'levels'));
    }

    public function show(Program $program)
    {
        $program->load([
            'category',
            'level',
            'affiliation',
            'courses' => fn($q) => $q->where('courses.is_active', true),
        ]);

        $relatedPrograms = Program::active()
            ->where('id', '!=', $program->id)
            ->when($program->category_id, fn($q) => $q->where('category_id', $program->category_id))
            ->with(['category', 'level'])
            ->limit(6)
            ->get();

        return view('modules.program.show', compact('program', 'relatedPrograms'));
    }
}
