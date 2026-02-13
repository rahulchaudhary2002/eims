<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Level;
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
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->whereHas('category', fn($qq) => $qq->where('slug', $request->category));
            })
            ->when($request->filled('level'), function ($q) use ($request) {
                $q->whereHas('level', fn($qq) => $qq->where('slug', $request->level));
            })
            ->when(count($categorySlugs), function ($q) use ($categorySlugs) {
                $q->whereHas('category', fn($qq) => $qq->whereIn('slug', $categorySlugs));
            })
            ->when(count($levelSlugs), function ($q) use ($levelSlugs) {
                $q->whereHas('level', fn($qq) => $qq->whereIn('slug', $levelSlugs));
            })
            ->when(count($durations), function ($q) use ($durations) {
                $q->whereIn('duration', $durations);
            })
            ->with(['category', 'level', 'affiliation'])
            ->paginate(6)
            ->withQueryString();

        $levels = Level::active()
            ->whereHas('courses')
            ->ordered()
            ->withCount('courses')
            ->get();

        $coursesByDuration = Course::active()
            ->select('duration', DB::raw('count(*) as total'))
            ->groupBy('duration')
            ->pluck('total', 'duration');

        $courseCategories = CourseCategory::whereHas('courses')
            ->withCount('courses')
            ->get();

        return view('modules.course.index', compact('courses', 'levels', 'coursesByDuration', 'courseCategories'));
    }

    public function show(Course $course)
    {
        $course->load(['level', 'affiliation', 'descriptions']);
        return view('modules.course.show', compact('course'));
    }
}
