<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ProfileUpdateRequest;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $student = $request->user('student');
        $student->load('profile');

        return view('modules.profile.edit', ['user' => $student]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $student = $request->user('student');
        $data    = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('students/avatars', 'public');
        } else {
            unset($data['avatar']);
        }

        if (isset($data['email']) && $data['email'] !== $student->email) {
            $data['email_verified_at'] = null;
        }

        $student->fill($data)->save();

        return back()->with('status', 'profile-updated');
    }

    public function updateExtended(Request $request): RedirectResponse
    {
        $request->validate([
            'guardian_name'       => ['nullable', 'string', 'max:255'],
            'guardian_phone'      => ['nullable', 'string', 'max:30'],
            'province'            => ['nullable', 'string', 'max:100'],
            'district'            => ['nullable', 'string', 'max:100'],
            'city'                => ['nullable', 'string', 'max:100'],
            'address'             => ['nullable', 'string', 'max:255'],
            'preferred_location'  => ['nullable', 'string', 'max:255'],
            'budget_min'          => ['nullable', 'integer', 'min:0'],
            'budget_max'          => ['nullable', 'integer', 'min:0'],
            'career_interests'    => ['nullable', 'array'],
            'career_interests.*'  => ['string', 'max:100'],
            'preferred_faculties' => ['nullable', 'array'],
            'preferred_faculties.*' => ['string', 'max:100'],
        ]);

        $student = $request->user('student');

        StudentProfile::updateOrCreate(
            ['student_id' => $student->id],
            $request->only([
                'guardian_name', 'guardian_phone',
                'province', 'district', 'city', 'address',
                'preferred_location', 'budget_min', 'budget_max',
                'career_interests', 'preferred_faculties',
            ])
        );

        return back()->with('status', 'profile-extended-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:student'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user('student')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:student'],
        ]);

        $student = $request->user('student');

        Auth::guard('student')->logout();

        $student->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
