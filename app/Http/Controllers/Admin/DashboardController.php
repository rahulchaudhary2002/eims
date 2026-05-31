<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Student;

class DashboardController extends Controller
{
    use ScopesForInstitution;

    public function index()
    {
        $scope = $this->institutionScope();

        if ($scope !== null) {
            // Normal user: scope all data to their current institution
            $institutionCount = 1; // only their own institution

            $pendingComission = 0;
            $receivedComission = 0;

            $recentApplications = Application::with(['student', 'institution', 'institutionProgram.program'])
                ->where('institution_id', $scope)
                ->latest()
                ->limit(10)
                ->get();

            $studentCount = Student::whereHas(
                'institutions',
                fn($q) => $q->where('institutions.id', $scope)->wherePivot('is_active', true)
            )->count();

        } else {
            // Super admin: unscoped
            $institutionCount = Institution::active()->count();
            $pendingComission = 0;
            $receivedComission = 0;
            $recentApplications = Application::with(['student', 'institution', 'institutionProgram.program'])->latest()->limit(10)->get();
            $studentCount = Student::count();
        }

        return view('admin.modules.dashboard.index', compact('institutionCount', 'pendingComission', 'receivedComission', 'recentApplications', 'studentCount'));
    }
}
