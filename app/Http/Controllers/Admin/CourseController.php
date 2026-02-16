<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Affiliation;
use App\Models\CourseCategory;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $courses = Course::with(['level', 'affiliation', 'programs'])->latest()->paginate(10);

        return view('admin.modules.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();
        $categories = CourseCategory::get();
        $programs = Program::active()->get();

        return view('admin.modules.course.create', compact('levels', 'affiliations', 'categories', 'programs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:course_categories,id',
            'program_ids' => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.content' => 'required|string',
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'level_id' => $validated['level_id'],
            'category_id' => $validated['category_id'],
            'affiliation_id' => $validated['affiliation_id'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'is_active' => $request->is_active ?? false,
        ]);

        $course->programs()->sync($validated['program_ids']);

        // Save sections
        if (!empty($request->sections)) {
            foreach ($request->sections as $index => $section) {
                $course->descriptions()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.course.index')->with('success', 'Course created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $course->load('programs');
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();
        $categories = CourseCategory::get();
        $programs = Program::active()->get();

        return view('admin.modules.course.edit', compact('course', 'levels', 'affiliations', 'categories', 'programs'));
    }

    public function show(Course $course)
    {
        $course->load(['level', 'affiliation', 'programs', 'descriptions']);
        return view('admin.modules.course.show', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:course_categories,id',
            'program_ids' => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.content' => 'required|string',
        ]);

        $course->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'description' => $validated['description'] ?? null,
            'level_id' => $validated['level_id'],
            'category_id' => $validated['category_id'],
            'affiliation_id' => $validated['affiliation_id'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'is_active' => $request->is_active ?? false,
        ]);

        $course->programs()->sync($validated['program_ids']);

        // Delete old sections
        $course->descriptions()->delete();

        // Save new sections
        if (!empty($request->sections)) {
            foreach ($request->sections as $index => $section) {
                $course->descriptions()->create([
                    'title' => $section['title'],
                    'content' => $section['content'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.course.index')->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()->route('admin.course.index')
            ->with('success', 'Course deleted successfully.');
    }
}
