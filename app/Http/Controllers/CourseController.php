<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $categorySlugs = (array) $request->input('categories', []);
        $levelSlugs    = (array) $request->input('levels', []);
        $durations     = (array) $request->input('durations', []);

        $courses = Course::active()
            ->whereHas('programs', fn($q) => $q->where('is_active', true))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('programs.category', fn($qq) => $qq->where('slug', $request->category));
            })
            ->when($request->filled('level'), function ($q) use ($request) {
                $q->whereHas('programs.level', fn($qq) => $qq->where('slug', $request->level));
            })
            ->when(count($categorySlugs), function ($q) use ($categorySlugs) {
                $q->whereHas('programs.category', fn($qq) => $qq->whereIn('slug', $categorySlugs));
            })
            ->when(count($levelSlugs), function ($q) use ($levelSlugs) {
                $q->whereHas('programs.level', fn($qq) => $qq->whereIn('slug', $levelSlugs));
            })
            ->when(count($durations), function ($q) use ($durations) {
                $q->whereHas('programs', fn($qq) => $qq->whereIn('duration', $durations));
            })
            ->with(['programs.level', 'programs.affiliation', 'programs.category'])
            ->paginate(6)
            ->withQueryString();

        $levels = Level::active()->ordered()->get();

        $levelCourseCounts = Course::active()
            ->join('course_program', 'courses.id', '=', 'course_program.course_id')
            ->join('programs', 'programs.id', '=', 'course_program.program_id')
            ->whereNotNull('programs.level_id')
            ->select('programs.level_id', DB::raw('count(distinct courses.id) as total'))
            ->groupBy('programs.level_id')
            ->pluck('total', 'programs.level_id');

        $levels = $levels->map(function ($level) use ($levelCourseCounts) {
            $level->courses_count = (int) ($levelCourseCounts[$level->id] ?? 0);
            return $level;
        })->filter(fn($level) => $level->courses_count > 0)->values();

        $coursesByDuration = Course::active()
            ->join('course_program', 'courses.id', '=', 'course_program.course_id')
            ->join('programs', 'programs.id', '=', 'course_program.program_id')
            ->whereNotNull('programs.duration')
            ->select('programs.duration', DB::raw('count(distinct courses.id) as total'))
            ->groupBy('programs.duration')
            ->pluck('total', 'programs.duration');

        $courseCategories = ProgramCategory::whereHas('programs')->withCount('programs')->get();

        $categoryCourseCounts = Course::active()->get();

        return view('modules.course.index', compact('courses', 'levels', 'coursesByDuration', 'courseCategories'));
    }

    public function show(Course $course)
    {
        $course->load(['programs.level', 'programs.affiliation', 'programs.category', 'descriptions']);
        return view('modules.course.show', compact('course'));
    }
}
