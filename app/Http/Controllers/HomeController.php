<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Institution;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()->where('type', 'college')->limit(6)->get();
        $courses = Course::active()->limit(6)->get();

        return view('modules.home.index', compact('colleges', 'courses'));
    }
}
