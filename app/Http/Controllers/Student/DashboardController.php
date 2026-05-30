<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user('student');
        $student->load('profile');

        $stats = [
            'applications'           => $student->applications()->count(),
            'applications_pending'   => $student->applications()->where('status', 'pending')->count(),
            'applications_approved'  => $student->applications()->whereIn('status', ['approved', 'accepted'])->count(),
            'scholarship_applications' => $student->scholarshipApplications()->count(),
            'favorite_institutions'  => $student->favoriteInstitutions()->count(),
            'followed_institutions'  => $student->followedInstitutions()->count(),
        ];

        $recentApplications = $student->applications()
            ->with(['institution', 'program'])
            ->latest()
            ->limit(5)
            ->get();

        $recommendations = $student->recommendations()
            ->with(['institution', 'program'])
            ->latest()
            ->limit(4)
            ->get();

        return view('student.dashboard', compact('student', 'stats', 'recentApplications', 'recommendations'));
    }
}
