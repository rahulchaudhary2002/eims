<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $programSlugs = (array) $request->input('programs', []);
        $levelSlugs    = (array) $request->input('levels', []);
        $durations     = (array) $request->input('durations', []);

        $courses = Course::query()
            ->active()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . trim($request->search) . '%');
            })
            ->whereHas('programs', function ($p) use ($request, $programSlugs, $levelSlugs, $durations) {
                $p->where('is_active', 1);

                $p->when($request->filled('program'), function ($pp) use ($request) {
                    $pp->where('slug', $request->program);
                });

                $p->when($request->filled('level'), function ($pp) use ($request) {
                    $pp->whereHas('level', fn($l) => $l->where('slug', $request->level));
                });

                $p->when(!empty($programSlugs), function ($pp) use ($programSlugs) {
                    $pp->whereIn('slug', $programSlugs);
                });

                $p->when(!empty($levelSlugs), function ($pp) use ($levelSlugs) {
                    $pp->whereHas('level', fn($l) => $l->whereIn('slug', $levelSlugs));
                });

                $p->when(!empty($durations), function ($pp) use ($durations) {
                    $pp->whereIn('duration', $durations);
                });
            })
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

        $programs = Program::whereHas('courses')->withCount('courses')->get();

        return view('modules.course.index', compact('courses', 'levels', 'coursesByDuration', 'programs'));
    }

    public function show(Course $course)
    {
        $course->load(['programs.level', 'programs.affiliation', 'programs.category', 'descriptions']);
        return view('modules.course.show', compact('course'));
    }
}
