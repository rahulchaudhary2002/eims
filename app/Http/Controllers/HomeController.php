<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Institution;
use App\Models\Level;
use App\Models\ProgramCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()
            ->with('institutionType')
            ->ofType('college')
            ->limit(6)
            ->get();
        $courses = Course::active()
            ->whereHas('programs', fn($q) => $q->where('is_active', true))
            ->with(['programs.level', 'programs.affiliation'])
            ->limit(6)
            ->get();
        $courseCategories = ProgramCategory::whereHas('programs.courses')->get();
        $levels = Level::active()->ordered()->get();

        return view('modules.home.index', compact('colleges', 'courses', 'courseCategories', 'levels'));
    }
}
