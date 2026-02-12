<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = Institution::active()
            ->with('institutionType')
            ->ofType('school')
            ->paginate(12);

        return view('modules.school.index', compact('schools'));
    }
}
