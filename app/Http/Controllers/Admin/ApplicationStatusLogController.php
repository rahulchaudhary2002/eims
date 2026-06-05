<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationStatusLogController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ApplicationStatusLog::with(['application.student', 'application.institution', 'changedBy']);
        $this->applyInstitutionScope($query);

        if ($applicationId = $request->input('application_id')) {
            $query->where('application_id', $applicationId);
        }
        if ($fromStatus = $request->input('from_status')) {
            $query->where('from_status', $fromStatus);
        }
        if ($toStatus = $request->input('to_status')) {
            $query->where('to_status', $toStatus);
        }
        if ($changedByType = $request->input('changed_by_type')) {
            $query->where('changed_by_type', $changedByType);
        }
        if ($createdFrom = $request->input('created_from')) {
            $query->whereDate('created_at', '>=', $createdFrom);
        }
        if ($createdTo = $request->input('created_to')) {
            $query->whereDate('created_at', '<=', $createdTo);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'student_id', 'institution_id']);
        $applications->load(['student', 'institution']);
        $statuses = Application::STATUSES;
        $changedByTypes = [
            User::class => 'Web User',
            Student::class => 'Student',
        ];

        return view('admin.modules.application-status-logs.index', compact(
            'logs',
            'applications',
            'statuses',
            'changedByTypes'
        ));
    }

    public function show(ApplicationStatusLog $applicationStatusLog): View
    {
        $this->authorizeLogAccess($applicationStatusLog);
        $applicationStatusLog->load(['application.student', 'application.institution', 'application.applicable', 'application.scholarship', 'changedBy']);

        return view('admin.modules.application-status-logs.show', compact('applicationStatusLog'));
    }

    public function destroy(ApplicationStatusLog $applicationStatusLog): RedirectResponse
    {
        $this->authorizeLogAccess($applicationStatusLog);
        $applicationStatusLog->delete();

        return redirect()->route('admin.application-status-logs.index')
            ->with('success', 'Application status log deleted successfully.');
    }

    private function applicationDropdownQuery(): Builder
    {
        $query = Application::orderByDesc('created_at');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('application', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function authorizeLogAccess(ApplicationStatusLog $log): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        $log->loadMissing('application');
        $institutionId = (int) $log->application?->institution_id;

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this application status log.'
        );
    }

    private function currentInstitutionIsAssigned(): bool
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
