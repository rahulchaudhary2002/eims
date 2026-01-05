<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::active()->paginate(12);

        return view('modules.course.index', compact('courses'));
    }
}
