<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentRecommendation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRecommendationController extends Controller
{
    public function index(Request $request): View
    {
        $studentId       = $request->user('student')->id;
        $recommendations = StudentRecommendation::where('student_id', $studentId)
            ->with(['institution', 'institutionProgram.program'])
            ->orderByDesc('score')
            ->paginate(12);

        return view('student.recommendations.index', compact('recommendations'));
    }

    public function show(Request $request, $recommendationId): View
    {
        $student = $request->user('student');

        $studentRecommendation = StudentRecommendation::with(['institution', 'institutionProgram.program'])->find($recommendationId);

        abort_unless($studentRecommendation, 404);
        abort_if($studentRecommendation->student_id !== $student->id, 403);

        if (! $studentRecommendation->is_viewed) {
            $studentRecommendation->update(['is_viewed' => true]);
        }

        return view('student.recommendations.show', compact('studentRecommendation'));
    }

    public function markViewed(Request $request, $recommendationId): RedirectResponse
    {
        $student = $request->user('student');

        $studentRecommendation = StudentRecommendation::find($recommendationId);

        abort_unless($studentRecommendation, 404);
        abort_if($studentRecommendation->student_id !== $student->id, 403);

        $studentRecommendation->update(['is_viewed' => true]);

        return back();
    }
}
