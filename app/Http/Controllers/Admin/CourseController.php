<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\Affiliation;
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
        $courses = Course::with(['level', 'affiliation'])->latest()->paginate(10);

        return view('admin.modules.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();

        return view('admin.modules.course.create', compact('levels', 'affiliations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code',
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Course::create([$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.course.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course): View
    {
        $levels = Level::active()->ordered()->get();
        $affiliations = Affiliation::active()->get();

        return view('admin.modules.course.edit', compact('course', 'levels', 'affiliations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $course->id,
            'description' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'affiliation_id' => 'nullable|exists:affiliations,id',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $course->update([$validated, 'is_active' => $request->is_active ?? false]);

        return redirect()->route('admin.course.index')
            ->with('success', 'Course updated successfully.');
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
