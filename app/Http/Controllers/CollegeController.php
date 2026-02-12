<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = Institution::active()
            ->with('institutionType')
            ->ofType('college')
            ->paginate(12);

        return view('modules.college.index', compact('colleges'));
    }
}
