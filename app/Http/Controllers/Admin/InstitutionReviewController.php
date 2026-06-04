<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionReviewRequest;
use App\Http\Requests\Admin\UpdateInstitutionReviewRequest;
use App\Models\Institution;
use App\Models\InstitutionReview;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionReviewController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionReview::with(['student', 'institution']);
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }
        if ($request->filled('is_approved')) {
            $query->where('is_approved', (bool) $request->input('is_approved'));
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $reviews      = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students     = Student::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.institution-reviews.index', compact('reviews', 'institutions', 'students'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students     = Student::orderBy('name')->get(['id', 'name']);
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.institution-reviews.create', compact('institutions', 'students', 'selectedInstitutionId'));
    }

    public function store(StoreInstitutionReviewRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $review = InstitutionReview::create($data);

        return redirect()->route('admin.institution-reviews.show', $review)
            ->with('success', 'Review created successfully.');
    }

    public function show(InstitutionReview $institutionReview): View
    {
        $this->authorizeInstitution((int) $institutionReview->institution_id);
        $institutionReview->load(['student', 'institution']);

        return view('admin.modules.institution-reviews.show', compact('institutionReview'));
    }

    public function edit(InstitutionReview $institutionReview): View
    {
        $this->authorizeInstitution((int) $institutionReview->institution_id);
        $institutionReview->load(['student', 'institution']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students     = Student::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.institution-reviews.edit', compact('institutionReview', 'institutions', 'students'));
    }

    public function update(UpdateInstitutionReviewRequest $request, InstitutionReview $institutionReview): RedirectResponse
    {
        $this->authorizeInstitution((int) $institutionReview->institution_id);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $institutionReview->update($data);

        return redirect()->route('admin.institution-reviews.show', $institutionReview)
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(InstitutionReview $institutionReview): RedirectResponse
    {
        $this->authorizeInstitution((int) $institutionReview->institution_id);
        $institutionReview->delete();

        return redirect()->route('admin.institution-reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function approve(InstitutionReview $institutionReview): RedirectResponse
    {
        $this->authorizeInstitution((int) $institutionReview->institution_id);
        $institutionReview->update(['is_approved' => true]);

        return back()->with('success', 'Review approved.');
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
