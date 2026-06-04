<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateStudentPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentSettingController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user('student');

        return view('student.settings.index', compact('student'));
    }

    public function updatePassword(UpdateStudentPasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $request->user('student')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
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
