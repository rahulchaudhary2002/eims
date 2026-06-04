<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRewardClaimRequest;
use App\Models\Application;
use App\Models\StudentRewardClaim;
use App\Models\StudentRewardClaimDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        $studentId = auth('student')->id();

        $applications = Application::where('student_id', $studentId)
            ->whereDoesntHave('rewardClaim')
            ->with([
                'institution:id,name',
                'institutionProgram.program:id,name',
                'admission:id,application_id,admission_number,admission_date',
            ])
            ->orderByDesc('id')
            ->get();

        $selectedApplicationId = old('application_id', $request->input('application_id'));

        return view('student.reward-claims.create', compact(
            'applications',
            'selectedApplicationId'
        ));
    }

    public function store(StoreStudentRewardClaimRequest $request): RedirectResponse
    {
        $studentId = auth('student')->id();
        $data      = $request->validated();
        $application = Application::with(['admission', 'latestReferral', 'rewardClaim'])
            ->whereKey($data['application_id'])
            ->where('student_id', $studentId)
            ->first();

        if (! $application) {
            throw ValidationException::withMessages([
                'application_id' => 'Please select a valid application.',
            ]);
        }

        if ($application->rewardClaim) {
            throw ValidationException::withMessages([
                'application_id' => 'A reward claim for this application has already been submitted.',
            ]);
        }

        $claimNumber = $this->generateClaimNumber();
        $admission = $application->admission;

        $claim = StudentRewardClaim::create([
            'claim_number'           => $claimNumber,
            'student_id'             => $studentId,
            'institution_id'         => $application->institution_id,
            'institution_program_id' => $application->institution_program_id,
            'application_id'         => $application->id,
            'admission_id'           => $admission?->id,
            'admission_date'         => $admission?->admission_date ?? $application->admitted_at?->toDateString(),
            'admission_number'       => $admission?->admission_number,
            'claimed_reward_amount'  => $data['claimed_reward_amount'] ?? 0,
            'payment_method'         => $data['payment_method'] ?? null,
            'student_note'           => $data['student_note'] ?? null,
            'status'                 => 'submitted',
            'submitted_at'           => now(),
        ]);

        foreach ($data['documents'] as $index => $documentData) {
            $file = $request->file("documents.$index.file");

            if (! $file) {
                continue;
            }

            $path         = $file->store('reward-claim-documents', 'public');

            StudentRewardClaimDocument::create([
                'student_reward_claim_id' => $claim->id,
                'document_type'           => $documentData['document_type'],
                'file_path'               => $path,
                'original_name'           => $file->getClientOriginalName(),
                'mime_type'               => $file->getMimeType(),
                'file_size'               => $file->getSize(),
            ]);
        }

        $referral = $application->latestReferral;

        if ($referral && $referral->is_profile_unlocked && $referral->protection_starts_at <= now() && $referral->protection_expires_at >= now()) {
            $claim->update(['referral_id' => $referral->id]);
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
