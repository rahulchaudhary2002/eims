<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionFollower;
use Illuminate\Http\RedirectResponse;

class StudentFollowController extends Controller
{
    public function store(Institution $institution): RedirectResponse
    {
        $student = auth('student')->user();
        InstitutionFollower::firstOrCreate([
            'institution_id' => $institution->id,
            'student_id'     => $student->id,
        ]);
        return back()->with('success', 'You are now following ' . $institution->name . '.');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $student = auth('student')->user();
        InstitutionFollower::where('institution_id', $institution->id)
            ->where('student_id', $student->id)
            ->delete();
        return back()->with('success', 'You have unfollowed ' . $institution->name . '.');
    }
}
