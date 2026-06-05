<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\UsesActiveInstitution;
use App\Models\Referral;
use App\Models\ReferralAccessLog;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionReferralController extends Controller
{
    use UsesActiveInstitution;
    public function index(Request $request): View
    {
        $institutionId = $this->activeInstitutionId();

        $query = Referral::with([
            'application',
            'student',
            'applicable',
            'referredBy',
        ])->where('institution_id', $institutionId);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $referrals        = $query->latest()->paginate(15)->withQueryString();
        $statuses         = Referral::STATUSES;
        $activeInstitution = $this->activeInstitution();

        return view('institution.referrals.index', compact('referrals', 'statuses', 'activeInstitution'));
    }

    public function show(Referral $referral): View
    {
        $this->authorizeReferralAccess($referral);

        $referral->load([
            'application',
            'student',
            'student.profile',
            'student.documents',
            'student.academicRecords',
            'institution',
            'applicable',
            'accessLogs.user',
            'referredBy',
            'rewardClaims',
        ]);

        // Log preview view on first visit
        if (is_null($referral->viewed_at)) {
            $referral->update(['viewed_at' => now(), 'status' => 'preview_viewed']);
            ReferralAccessLog::create([
                'referral_id'    => $referral->id,
                'institution_id' => $referral->institution_id,
                'user_id'        => auth('web')->id(),
                'action'         => 'preview_viewed',
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
            ]);
        }

        $masked = ! $referral->is_profile_unlocked;
        $maskedData = $masked && $referral->student
            ? $this->getMaskedStudentData($referral->student)
            : null;

        return view('institution.referrals.show', compact('referral', 'masked', 'maskedData'));
    }

    public function requestUnlock(Request $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);

        $request->validate([
            'terms_accepted' => ['required', 'accepted'],
        ]);

        if ($referral->is_profile_unlocked) {
            return back()->withErrors(['terms_accepted' => 'Profile is already unlocked for this referral.']);
        }

        $protectionExpiry = today()->addDays(Referral::PROTECTION_DAYS);

        $referral->update([
            'is_profile_unlocked'   => true,
            'profile_unlocked_at'   => now(),
            'profile_unlocked_by'   => auth('web')->id(),
            'agreement_accepted_at' => now(),
            'protection_starts_at'  => today(),
            'protection_expires_at' => $protectionExpiry,
            'status'                => 'full_profile_unlocked',
            'unlock_ip'             => $request->ip(),
            'unlock_user_agent'     => $request->userAgent(),
        ]);

        ReferralAccessLog::create([
            'referral_id'    => $referral->id,
            'institution_id' => $referral->institution_id,
            'user_id'        => auth('web')->id(),
            'action'         => 'full_profile_unlocked',
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        if ($referral->application) {
            $referral->application->update(['status' => 'institution_reviewing']);
        }

        return back()->with('success', 'Student profile unlocked. Referral protection period has started.');
    }

    public function accept(Request $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);

        $request->validate([
            'institution_response_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $referral->update([
            'status'                    => 'accepted',
            'institution_response_note' => $request->input('institution_response_note'),
        ]);

        ReferralAccessLog::create([
            'referral_id'    => $referral->id,
            'institution_id' => $referral->institution_id,
            'user_id'        => auth('web')->id(),
            'action'         => 'accepted',
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return back()->with('success', 'Referral accepted successfully.');
    }

    public function reject(Request $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);

        $request->validate([
            'institution_response_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $referral->update([
            'status'                    => 'rejected',
            'institution_response_note' => $request->input('institution_response_note'),
        ]);

        ReferralAccessLog::create([
            'referral_id'    => $referral->id,
            'institution_id' => $referral->institution_id,
            'user_id'        => auth('web')->id(),
            'action'         => 'rejected',
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        return back()->with('success', 'Referral rejected. Protection period remains active if profile was unlocked.');
    }

    public function requestMoreInfo(Request $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);

        ReferralAccessLog::create([
            'referral_id'    => $referral->id,
            'institution_id' => $referral->institution_id,
            'user_id'        => auth('web')->id(),
            'action'         => 'full_profile_requested',
            'ip_address'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
        ]);

        if ($referral->application) {
            $referral->application->update(['status' => 'institution_requested_documents']);
        }

        return back()->with('success', 'More information request has been sent to the platform.');
    }

    protected function authorizeReferralAccess(Referral $referral): void
    {
        abort_unless(
            (int) $referral->institution_id === $this->activeInstitutionId(),
            403,
            'You do not have access to this referral.'
        );
    }

    protected function getMaskedStudentData(Student $student): array
    {
        $nameParts    = explode(' ', trim($student->name ?? ''));
        $firstName    = $nameParts[0] ?? '';
        $lastName     = count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '';
        $firstInitial = $firstName ? strtoupper($firstName[0]) : '';
        $lastInitial  = $lastName ? strtoupper($lastName[0]) : '';
        $maskedName   = $firstInitial . '*** ' . $lastInitial . '***';

        $profile = $student->profile;

        $educationLevel = $student->academicRecords?->sortByDesc('id')->first()?->level;

        return [
            'name'            => $maskedName,
            'country'         => $profile?->country,
            'nationality'     => $profile?->nationality,
            'education_level' => $educationLevel,
        ];
    }

}
