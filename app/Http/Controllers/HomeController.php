<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Institution;
use App\Models\Level;
use App\Models\Program;
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
            ->with(['programs.faculty'])
            ->limit(6)
            ->get();

        $programs = Program::active()->with('faculty')->get();

        $levels = Level::active()->ordered()->get();

        $institutionCount = Institution::active()->count();

        $featuredPrograms = Program::active()
            ->with('faculty')
            ->limit(3)
            ->get();

        return view('modules.home.index', compact(
            'colleges', 'courses', 'programs', 'levels', 'institutionCount', 'featuredPrograms'
        ));
    }
}
