<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Institution;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()->where('type', 'college')->paginate(12);
        $schools = Institution::active()->where('type', 'school')->paginate(12);
        $courses = Course::active()->paginate(12);

        return view('modules.home.index', compact('colleges', 'schools', 'courses'));
    }
}
