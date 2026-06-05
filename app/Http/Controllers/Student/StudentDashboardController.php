<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user('student');
        $student->load('profile');

        $studentId = $student->id;

        $stats = [
            'applications'             => Application::where('student_id', $studentId)->count(),
            'applications_pending'     => Application::where('student_id', $studentId)->whereIn('status', ['draft', 'submitted', 'under_review'])->count(),
            'applications_approved'    => Application::where('student_id', $studentId)->where('status', 'admitted')->count(),
            'scholarship_applications' => \App\Models\Application::where('student_id', $studentId)->whereNotNull('scholarship_id')->count(),
            'favorite_institutions'    => \App\Models\StudentFavoriteInstitution::where('student_id', $studentId)->count(),
            'followed_institutions'    => 0,
        ];

        $recentApplications = Application::where('student_id', $studentId)
            ->with(['institution', 'applicable', 'scholarship'])
            ->latest()
            ->limit(4)
            ->get();

        $recommendations = \App\Models\StudentRecommendation::where('student_id', $studentId)
            ->with(['institution', 'applicable'])
            ->latest()
            ->limit(4)
            ->get();

        $percent = $student->profileCompletion();

        return view('student.dashboard', compact(
            'student', 'stats', 'recentApplications', 'recommendations', 'percent'
        ));
    }
}
