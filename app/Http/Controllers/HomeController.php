<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Institution;
use App\Models\Level;
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
        $courses = Course::active()->limit(6)->get();
        $courseCategories = CourseCategory::whereHas('courses')->get();
        $levels = Level::active()->ordered()->get();

        return view('modules.home.index', compact('colleges', 'courses', 'courseCategories', 'levels'));
    }
}
