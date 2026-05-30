<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $programSlugs = (array) $request->input('programs', []);
        $levelValues  = (array) $request->input('levels', []);

        $courses = Course::active()
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . trim($request->search) . '%'))
            ->whereHas('programs', function ($p) use ($request, $programSlugs, $levelValues) {
                $p->where('is_active', 1);

                $p->when($request->filled('program'), fn($pp) => $pp->where('slug', $request->program));

                $p->when($request->filled('level'), fn($pp) => $pp->where('level', $request->level));

                $p->when(!empty($programSlugs), fn($pp) => $pp->whereIn('slug', $programSlugs));

                $p->when(!empty($levelValues), fn($pp) => $pp->whereIn('level', $levelValues));
            })
            ->with(['programs.faculty'])
            ->paginate(6)
            ->withQueryString();

        // Distinct level strings from programs that have active courses
        $levels = Program::active()
            ->whereHas('courses', fn($q) => $q->where('is_active', true))
            ->distinct()
            ->orderBy('level')
            ->pluck('level')
            ->filter()
            ->map(fn($l) => (object) ['slug' => $l, 'name' => ucfirst($l), 'courses_count' => 0]);

        $programs = Program::active()->withCount('courses')->get();

        // Empty — duration is not a column on Program; pass an empty collection to avoid view errors
        $coursesByDuration = collect();

        return view('modules.course.index', compact('courses', 'levels', 'coursesByDuration', 'programs'));
    }

    public function show(Course $course)
    {
        $course->load(['programs.faculty', 'descriptions']);
        return view('modules.course.show', compact('course'));
    }
}
