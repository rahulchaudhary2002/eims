<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Referral;
use App\Models\Student;
use App\Models\StudentRewardClaim;
use App\Models\StudentRewardClaimDocument;
use App\Models\StudentRewardPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRewardClaimController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = StudentRewardClaim::with([
            'student',
            'institution',
            'institutionProgram',
            'referral',
        ]);

        $scope = $this->institutionScope();
        if ($scope !== null) {
            if ($scope === 0) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('submitted_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('submitted_at', '<=', $dateTo);
        }

        $claims = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $students = Student::orderBy('name')->get(['id', 'name']);

        return view('admin.modules.student-reward-claims.index', compact('claims', 'institutions', 'students'));
    }

    public function show(StudentRewardClaim $studentRewardClaim): View
    {
        $studentRewardClaim->load([
            'student',
            'institution',
            'institutionProgram',
            'application.admission',
            'application.allReferrals',
            'referral',
            'admission',
            'documents.verifiedBy',
            'payments.paidBy',
            'verifiedBy',
            'approvedBy',
            'paidBy',
        ]);

        $availableReferrals = $studentRewardClaim->application?->allReferrals
            ?->sortByDesc('id')
            ->values() ?? collect();
        $linkedAdmission = $studentRewardClaim->admission ?? $studentRewardClaim->application?->admission;

        return view('admin.modules.student-reward-claims.show', compact(
            'studentRewardClaim',
            'availableReferrals',
            'linkedAdmission'
        ));
    }

    public function updateStatus(Request $request, StudentRewardClaim $studentRewardClaim): RedirectResponse
    {
        $request->validate([
            'status'                => ['required', 'in:' . implode(',', array_keys(StudentRewardClaim::STATUSES))],
            'approved_reward_amount' => ['nullable', 'numeric', 'min:0'],
            'rejection_reason'      => ['nullable', 'string', 'max:2000'],
            'payment_method'        => ['nullable', 'in:' . implode(',', array_keys(StudentRewardClaim::PAYMENT_METHODS))],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $status = $request->input('status');
        $data   = ['status' => $status];

        if ($status === 'verified') {
            $data['verified_at'] = now();
            $data['verified_by'] = auth('web')->id();
        } elseif ($status === 'approved') {
            $data['approved_at']            = now();
            $data['approved_by']            = auth('web')->id();
            $data['approved_reward_amount'] = $request->input('approved_reward_amount');
        } elseif ($status === 'paid') {
            $data['paid_at'] = now();
            $data['paid_by'] = auth('web')->id();
            $data['payment_method'] = $request->input('payment_method') ?: $studentRewardClaim->payment_method;

            StudentRewardPayment::create([
                'student_reward_claim_id' => $studentRewardClaim->id,
                'student_id'              => $studentRewardClaim->student_id,
                'amount'                  => $studentRewardClaim->approved_reward_amount,
                'payment_method'          => $request->input('payment_method') ?: $studentRewardClaim->payment_method,
                'transaction_reference'   => $request->input('transaction_reference'),
                'status'                  => 'paid',
                'paid_by'                 => auth('web')->id(),
                'paid_at'                 => now(),
            ]);
        } elseif ($status === 'rejected') {
            $request->validate([
                'rejection_reason' => ['required', 'string', 'max:2000'],
            ]);
            $data['rejection_reason'] = $request->input('rejection_reason');
        }

        $studentRewardClaim->update($data);

        return back()->with('success', 'Reward claim status updated.');
    }

    public function verifyDocument(Request $request, StudentRewardClaimDocument $studentRewardClaimDocument): RedirectResponse
    {
        $request->validate([
            'verification_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $studentRewardClaimDocument->update([
            'is_verified'       => true,
            'verified_by'       => auth('web')->id(),
            'verified_at'       => now(),
            'verification_note' => $request->input('verification_note'),
        ]);

        return back()->with('success', 'Document verified successfully.');
    }

    public function linkReferral(Request $request, StudentRewardClaim $studentRewardClaim): RedirectResponse
    {
        $request->validate([
            'referral_id' => ['required', 'exists:referrals,id'],
        ]);

        $referralId = (int) $request->input('referral_id');
        $belongsToApplication = $studentRewardClaim->application()
            ->whereHas('allReferrals', fn (Builder $query) => $query->whereKey($referralId))
            ->exists();

        if (! $belongsToApplication) {
            return back()->withErrors([
                'referral_id' => 'Please select a referral for this application.',
            ]);
        }

        $studentRewardClaim->update([
            'referral_id' => $referralId,
        ]);

        return back()->with('success', 'Referral linked successfully.');
    }

    public function linkAdmission(Request $request, StudentRewardClaim $studentRewardClaim): RedirectResponse
    {
        $request->validate([
            'admission_id' => ['required', 'exists:admissions,id'],
        ]);

        $studentRewardClaim->update([
            'admission_id' => $request->input('admission_id'),
        ]);

        return back()->with('success', 'Admission linked successfully.');
    }

    public function storeFromReferral(Request $request, Referral $referral): RedirectResponse
    {
        abort_unless($referral->status === 'accepted', 422, 'Reward claim can only be created for accepted referrals.');

        $request->validate([
            'payment_method'        => ['required', 'in:' . implode(',', array_keys(StudentRewardClaim::PAYMENT_METHODS))],
            'claimed_reward_amount' => ['nullable', 'numeric', 'min:0'],
            'admin_note'            => ['nullable', 'string', 'max:2000'],
        ]);

        if ($referral->rewardClaims()->exists()) {
            return back()->withErrors(['payment_method' => 'A reward claim already exists for this referral.']);
        }

        $claimNumber = $this->generateClaimNumber();
        $admission   = $referral->admission;

        StudentRewardClaim::create([
            'claim_number'           => $claimNumber,
            'student_id'             => $referral->student_id,
            'institution_id'         => $referral->institution_id,
            'institution_program_id' => $referral->institution_program_id,
            'application_id'         => $referral->application_id,
            'referral_id'            => $referral->id,
            'admission_id'           => $admission?->id,
            'admission_date'         => $admission?->admission_date?->toDateString(),
            'admission_number'       => $admission?->admission_number,
            'claimed_reward_amount'  => $request->input('claimed_reward_amount', 0),
            'payment_method'         => $request->input('payment_method'),
            'admin_note'             => $request->input('admin_note'),
            'status'                 => 'submitted',
            'submitted_at'           => now(),
        ]);

        return back()->with('success', 'Reward claim created successfully.');
    }

    private function generateClaimNumber(): string
    {
        do {
            $number = 'RWD-' . now()->format('Ymd') . '-' . random_int(1000, 9999);
        } while (StudentRewardClaim::where('claim_number', $number)->exists());

        return $number;
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
                    ->whereHas('users', fn (Builder $institutionQuery) => $institutionQuery
                        ->where('users.id', auth('web')->id())
                        ->wherePivot('is_active', true));
            }
        }

        return $query;
    }

    private function currentInstitutionIsAssigned(): bool
    {
        /** @var User|null $user */
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        if (! $user) {
            return false;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
