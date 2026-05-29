<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

            $studentCount = User::whereHas(
                'institutions',
                fn($q) => $q->where('institutions.id', $scope)->wherePivot('is_active', true)
            )->count();

            $institutionTypes = $this->institutionTypesOverview($scope);
        } else {
            // Super admin: unscoped
            $institutionCount = Institution::active()->count();
            $pendingComission = 0;
            $receivedComission = 0;
            $recentApplications = Application::with(['student', 'institution', 'institutionProgram.program'])->latest()->limit(10)->get();
            $studentCount = User::count();
            $institutionTypes = $this->institutionTypesOverview();
        }

        return view('admin.modules.dashboard.index', compact('institutionCount', 'pendingComission', 'receivedComission', 'recentApplications', 'studentCount', 'institutionTypes'));
    }

    private function institutionTypesOverview(?int $institutionId = null)
    {
        return Institution::query()
            ->select('type', DB::raw('count(*) as institutions_count'))
            ->when($institutionId, fn($query) => $query->whereKey($institutionId))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderBy('type')
            ->get()
            ->map(fn($row) => (object) [
                'name' => Str::headline($row->type),
                'slug' => $row->type,
                'institutions_count' => $row->institutions_count,
            ]);
    }
}
