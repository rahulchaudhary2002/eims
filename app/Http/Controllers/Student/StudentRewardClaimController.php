<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRewardClaimRequest;
use App\Models\Referral;
use App\Models\Admission;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\StudentRewardClaim;
use App\Models\StudentRewardClaimDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRewardClaimController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = auth('student')->id();

        $claims = StudentRewardClaim::where('student_id', $studentId)
            ->with(['institution', 'institutionProgram', 'documents'])
            ->withCount('documents')
            ->latest()
            ->paginate(10);

        return view('student.reward-claims.index', compact('claims'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::where('status', 'active')->orderBy('name')->get(['id', 'name']);

        $selectedInstitutionId = $request->input('institution_id');
        $selectedApplicationId = $request->input('application_id');

        $programs = null;
        if ($selectedInstitutionId) {
            $programs = InstitutionProgram::where('institution_id', $selectedInstitutionId)
                ->orderBy('id')
                ->get(['id', 'institution_id']);
        }

        return view('student.reward-claims.create', compact(
            'institutions',
            'programs',
            'selectedInstitutionId',
            'selectedApplicationId'
        ));
    }

    public function store(StoreStudentRewardClaimRequest $request): RedirectResponse
    {
        $studentId = auth('student')->id();
        $data      = $request->validated();

        $claimNumber = $this->generateClaimNumber();

        $claim = StudentRewardClaim::create([
            'claim_number'           => $claimNumber,
            'student_id'             => $studentId,
            'institution_id'         => $data['institution_id'],
            'institution_program_id' => $data['institution_program_id'] ?? null,
            'application_id'         => $data['application_id'] ?? null,
            'admission_date'         => $data['admission_date'],
            'admission_number'       => $data['admission_number'] ?? null,
            'intake'                 => $data['intake'] ?? null,
            'claimed_reward_amount'  => $data['claimed_reward_amount'] ?? null,
            'payment_method'         => $data['payment_method'] ?? null,
            'student_note'           => $data['student_note'] ?? null,
            'status'                 => 'submitted',
            'submitted_at'           => now(),
        ]);

        // Upload and store documents
        foreach ($request->file('documents', []) as $documentData) {
            $file         = $documentData['file'];
            $documentType = $documentData['document_type'];
            $path         = $file->store('reward-claim-documents', 'public');

            StudentRewardClaimDocument::create([
                'student_reward_claim_id' => $claim->id,
                'document_type'           => $documentType,
                'file_path'               => $path,
                'original_filename'       => $file->getClientOriginalName(),
                'mime_type'               => $file->getMimeType(),
            ]);
        }

        // Auto-link application referral: find protected referral for same student+institution
        $referral = Referral::where('student_id', $studentId)
            ->where('institution_id', $data['institution_id'])
            ->where('is_profile_unlocked', true)
            ->where('protection_starts_at', '<=', now())
            ->where('protection_expires_at', '>=', now())
            ->latest()
            ->first();

        if ($referral) {
            $claim->update(['referral_id' => $referral->id]);
        }

        // Auto-link admission: find admission for same student+institution
        $admission = Admission::where('student_id', $studentId)
            ->where('institution_id', $data['institution_id'])
            ->latest()
            ->first();

        if ($admission) {
            $claim->update(['admission_id' => $admission->id]);
        }

        return redirect()->route('student.reward-claims.show', $claim)
            ->with('success', 'Reward claim submitted successfully.');
    }

    public function show(StudentRewardClaim $rewardClaim): View
    {
        abort_unless($rewardClaim->student_id === auth('student')->id(), 403);

        $rewardClaim->load([
            'institution',
            'institutionProgram',
            'documents.verifiedBy',
            'payments',
            'referral',
            'admission',
        ]);

        return view('student.reward-claims.show', compact('rewardClaim'));
    }

    private function generateClaimNumber(): string
    {
        do {
            $number = 'RWD-' . now()->format('Ymd') . '-' . random_int(1000, 9999);
        } while (StudentRewardClaim::where('claim_number', $number)->exists());

        return $number;
    }
}
