<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\StudentCompareItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCompareController extends Controller
{
    private const MAX_ITEMS = 4;

    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $items     = StudentCompareItem::where('student_id', $studentId)
            ->with(['institution', 'institutionProgram.program'])
            ->get();

        return view('student.compare.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_id'         => ['required', 'integer', 'exists:institutions,id'],
            'institution_program_id' => ['nullable', 'integer', 'exists:institution_programs,id'],
        ]);

        $studentId = $request->user('student')->id;

        $count = StudentCompareItem::where('student_id', $studentId)->count();
        if ($count >= self::MAX_ITEMS) {
            return back()->with('error', 'Compare list is full. Remove an item first (max ' . self::MAX_ITEMS . ').');
        }

        $exists = StudentCompareItem::where('student_id', $studentId)
            ->where('institution_id', $request->institution_id)
            ->where('institution_program_id', $request->institution_program_id)
            ->exists();

        if (!$exists) {
            StudentCompareItem::create([
                'student_id'             => $studentId,
                'institution_id'         => $request->institution_id,
                'institution_program_id' => $request->institution_program_id,
            ]);
        }

        return back()->with('success', 'Added to compare list.');
    }

    public function destroy(Request $request, StudentCompareItem $compare): RedirectResponse
    {
        abort_if($compare->student_id !== $request->user('student')->id, 403);

        $compare->delete();

        return back()->with('success', 'Removed from compare list.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        StudentCompareItem::where('student_id', $request->user('student')->id)->delete();

        return back()->with('success', 'Compare list cleared.');
    }
}
