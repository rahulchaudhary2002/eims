<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()
            ->where('type', 'college')
            ->paginate(12);

        return view('modules.college.index', compact('colleges'));
    }
}
