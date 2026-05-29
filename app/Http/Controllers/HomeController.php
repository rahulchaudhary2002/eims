<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Institution;
use App\Models\Level;
use App\Models\Program;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()
            ->where('type', 'college')
            ->limit(6)
            ->get();
        $courses = Course::active()
            ->whereHas('programs', fn($q) => $q->where('is_active', true))
            ->with(['programs.level', 'programs.affiliation'])
            ->limit(6)
            ->get();
        $programs = Program::whereHas('courses')->withCount('courses')->get();
        $levels = Level::active()->ordered()->get();
        $institutionCount = Institution::active()->count();
        $featuredPrograms = Program::active()
            ->whereHas('courses', fn($q) => $q->where('is_active', true))
            ->with(['category', 'level'])
            ->withCount([
                'courses as active_courses_count' => fn($q) => $q->where('courses.is_active', true),
            ])
            ->orderBy('active_courses_count', 'desc')
            ->limit(3)
            ->get();

        return view('modules.home.index', compact('colleges', 'courses', 'programs', 'levels', 'institutionCount', 'featuredPrograms'));
    }
}
