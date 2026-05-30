<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\StudentFavoriteInstitution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentFavoriteInstitutionController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $favorites = StudentFavoriteInstitution::where('student_id', $studentId)
            ->with('institution')
            ->latest()
            ->paginate(12);

        return view('student.favorites.index', compact('favorites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['institution_id' => ['required', 'integer', 'exists:institutions,id']]);

        $studentId = $request->user('student')->id;
        $institution = Institution::findOrFail($request->institution_id);

        $exists = StudentFavoriteInstitution::where('student_id', $studentId)
            ->where('institution_id', $institution->id)
            ->exists();

        if (!$exists) {
            StudentFavoriteInstitution::create([
                'student_id'     => $studentId,
                'institution_id' => $institution->id,
            ]);
        }

        return back()->with('success', "{$institution->name} added to favorites.");
    }

    public function destroy(Request $request, StudentFavoriteInstitution $favorite): RedirectResponse
    {
        abort_if($favorite->student_id !== $request->user('student')->id, 403);

        $favorite->delete();

        return back()->with('success', 'Removed from favorites.');
    }
}
