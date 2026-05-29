<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRecommendationRequest;
use App\Http\Requests\Admin\UpdateStudentRecommendationRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Student;
use App\Models\StudentRecommendation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentRecommendationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = StudentRecommendation::with(['student', 'institution', 'institutionProgram.program']);
        $this->applyInstitutionScope($query);

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($programId = $request->input('institution_program_id')) {
            $query->where('institution_program_id', $programId);
        }
        if ($request->input('is_viewed') !== null && $request->input('is_viewed') !== '') {
            $query->where('is_viewed', (bool) $request->input('is_viewed'));
        }
        if ($scoreMin = $request->input('score_min')) {
            $query->where('score', '>=', $scoreMin);
        }
        if ($scoreMax = $request->input('score_max')) {
            $query->where('score', '<=', $scoreMax);
        }

        $recommendations = $query->orderByDesc('score')->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);

        return view('admin.modules.student-recommendations.index', compact(
            'recommendations',
            'students',
            'institutions',
            'institutionPrograms'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.student-recommendations.create', compact(
            'students',
            'institutions',
            'institutionPrograms',
            'selectedStudentId',
            'selectedInstitutionId'
        ));
    }

    public function store(StoreStudentRecommendationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['is_viewed'] = $request->boolean('is_viewed');
        $data['reasons'] = $this->parseReasons($data['reasons_text'] ?? '');
        unset($data['reasons_text']);

        $recommendation = StudentRecommendation::create($data);

        return redirect()->route('admin.student-recommendations.show', $recommendation)
            ->with('success', 'Recommendation created successfully.');
    }

    public function show(StudentRecommendation $studentRecommendation): View
    {
        $this->authorizeRecommendationAccess($studentRecommendation);
        $studentRecommendation->load(['student', 'institution', 'institutionProgram.program']);

        return view('admin.modules.student-recommendations.show', compact('studentRecommendation'));
    }

    public function edit(StudentRecommendation $studentRecommendation): View
    {
        $this->authorizeRecommendationAccess($studentRecommendation);
        $studentRecommendation->load(['student', 'institution', 'institutionProgram']);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);

        return view('admin.modules.student-recommendations.edit', compact(
            'studentRecommendation',
            'students',
            'institutions',
            'institutionPrograms'
        ));
    }

    public function update(UpdateStudentRecommendationRequest $request, StudentRecommendation $studentRecommendation): RedirectResponse
    {
        $this->authorizeRecommendationAccess($studentRecommendation);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['is_viewed'] = $request->boolean('is_viewed');
        $data['reasons'] = $this->parseReasons($data['reasons_text'] ?? '');
        unset($data['reasons_text']);

        $studentRecommendation->update($data);

        return redirect()->route('admin.student-recommendations.show', $studentRecommendation)
            ->with('success', 'Recommendation updated successfully.');
    }

    public function destroy(StudentRecommendation $studentRecommendation): RedirectResponse
    {
        $this->authorizeRecommendationAccess($studentRecommendation);
        $studentRecommendation->delete();

        return redirect()->route('admin.student-recommendations.index')
            ->with('success', 'Recommendation deleted successfully.');
    }

    public function markViewed(StudentRecommendation $studentRecommendation): RedirectResponse
    {
        $this->authorizeRecommendationAccess($studentRecommendation);
        $studentRecommendation->update(['is_viewed' => true]);

        return back()->with('success', 'Recommendation marked as viewed.');
    }

    private function parseReasons(string $text): array
    {
        return array_values(array_filter(
            array_map('trim', explode("\n", $text)),
            fn ($line) => $line !== ''
        ));
    }

    private function institutionDropdownQuery(): Builder
    {
        $query = Institution::orderBy('name');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('id', $scope)
                    ->whereHas('users', fn (Builder $q) => $q->where('users.id', auth('web')->id())->wherePivot('is_active', true));
            }
        }

        return $query;
    }

    private function institutionProgramDropdownQuery(): Builder
    {
        $query = InstitutionProgram::orderBy('id');
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
                $query->where('institution_id', $scope);
            }
        }
    }

    private function authorizeRecommendationAccess(StudentRecommendation $recommendation): void
    {
        $this->authorizeInstitution((int) $recommendation->institution_id);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this institution.'
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
