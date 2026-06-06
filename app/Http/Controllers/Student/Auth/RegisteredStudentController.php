<?php

namespace App\Http\Controllers\Student\Auth;

use App\Events\NewRegistrationNotification;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Notifications\NewRegistrationAlert;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredStudentController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', 'unique:students,email'],
            'phone'      => ['required', 'string', 'max:30', 'unique:students,phone'],
            'password'   => ['required', 'confirmed', Password::min(8)],
            'terms'      => ['accepted'],
        ]);

        $student = Student::create([
            'name'      => trim($request->first_name . ' ' . $request->last_name),
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        event(new Registered($student));

        // Notify all platform users in real-time
        $platformUsers = User::where('is_active', true)
            ->where(fn ($q) => $q->where('is_super_admin', true)->orWhere('is_platform_user', true))
            ->get();
        $studentUrl = route('admin.students.show', $student->id);
        Notification::send($platformUsers, new NewRegistrationAlert('student', $student->name, $student->email, $studentUrl));
        event(new NewRegistrationNotification('student', $student->name, $student->email, $student->id, $studentUrl));

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard');
    }
}
