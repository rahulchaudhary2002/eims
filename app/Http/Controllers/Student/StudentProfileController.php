<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateStudentProfileRequest;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user('student');
        $student->load(['profile', 'academicRecords']);

        $profileCompletion = $this->calculateCompletion($student);

        return view('student.profile.index', compact('student', 'profileCompletion'));
    }

    public function update(UpdateStudentProfileRequest $request): RedirectResponse
    {
        $student = $request->user('student');
        $data    = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            $data['avatar'] = $request->file('avatar')
                ->store("students/{$student->id}/avatar", 'public');
        } else {
            unset($data['avatar']);
        }

        if (isset($data['email']) && $data['email'] !== $student->email) {
            $data['email_verified_at'] = null;
        }

        $student->fill($data)->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateExtended(Request $request): RedirectResponse
    {
        $request->validate([
            'guardian_name'         => ['nullable', 'string', 'max:255'],
            'guardian_phone'        => ['nullable', 'string', 'max:30'],
            'province'              => ['nullable', 'string', 'max:100'],
            'district'              => ['nullable', 'string', 'max:100'],
            'city'                  => ['nullable', 'string', 'max:100'],
            'address'               => ['nullable', 'string', 'max:500'],
            'budget_min'            => ['nullable', 'integer', 'min:0'],
            'budget_max'            => ['nullable', 'integer', 'min:0'],
            'preferred_location'    => ['nullable', 'string', 'max:255'],
            'career_interests'      => ['nullable', 'array'],
            'career_interests.*'    => ['string', 'max:100'],
            'preferred_faculties'   => ['nullable', 'array'],
            'preferred_faculties.*' => ['string', 'max:100'],
        ]);

        $student = $request->user('student');

        StudentProfile::updateOrCreate(
            ['student_id' => $student->id],
            $request->only([
                'guardian_name', 'guardian_phone',
                'province', 'district', 'city', 'address',
                'budget_min', 'budget_max', 'preferred_location',
                'career_interests', 'preferred_faculties',
            ])
        );

        return back()->with('success', 'Additional details updated successfully.');
    }

    private function calculateCompletion($student): int
    {
        $fields  = ['name', 'email', 'phone', 'date_of_birth', 'gender', 'avatar'];
        $filled  = collect($fields)->filter(fn($f) => !empty($student->$f))->count();
        $profile = $student->profile;
        $pFields = ['province', 'district', 'city', 'address', 'guardian_name', 'guardian_phone'];
        $pFilled = $profile ? collect($pFields)->filter(fn($f) => !empty($profile->$f))->count() : 0;
        $total   = count($fields) + count($pFields);

        return (int) round((($filled + $pFilled) / $total) * 100);
    }
}
