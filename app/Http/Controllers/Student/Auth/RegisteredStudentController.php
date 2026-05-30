<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredStudentController extends Controller
{
    public function create(): View
    {
        $educationLevels = collect([
            ['id' => 'see', 'name' => 'SEE / Secondary'],
            ['id' => 'plus-two', 'name' => '+2 / Higher Secondary'],
            ['id' => 'bachelor', 'name' => 'Bachelor'],
            ['id' => 'master', 'name' => 'Master'],
            ['id' => 'diploma', 'name' => 'Diploma / Certificate'],
        ])->map(fn(array $level) => (object) $level);

        $educationFields = collect([
            'Management',
            'Science',
            'Engineering',
            'Medical',
            'Information Technology',
            'Humanities',
            'Law',
            'Education',
            'Arts & Design',
        ])->map(fn(string $name) => (object) ['name' => $name]);

        return view('auth.register', compact('educationLevels', 'educationFields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'  => ['required', 'string', 'max:100'],
            'last_name'   => ['required', 'string', 'max:100'],
            'email'       => ['required', 'email', 'max:255', 'unique:students,email'],
            'phone'       => ['required', 'string', 'max:30', 'unique:students,phone'],
            'dob'         => ['required', 'date', 'before:-13 years'],
            'address'     => ['nullable', 'string', 'max:255'],
            'education_level_id' => ['nullable', 'string', 'max:100'],
            'field_of_interest' => ['nullable', 'string', 'max:255'],
            'password'    => ['required', 'confirmed', Password::min(8)],
            'terms'       => ['accepted'],
        ]);

        $student = Student::create([
            'name'          => trim($request->first_name . ' ' . $request->last_name),
            'email'         => $request->email,
            'phone'         => $request->phone,
            'date_of_birth' => $request->dob,
            'password'      => Hash::make($request->password),
            'is_active'     => true,
        ]);

        if ($request->filled('address') || $request->filled('field_of_interest')) {
            StudentProfile::create([
                'student_id'       => $student->id,
                'address'          => $request->address,
                'career_interests' => $request->filled('field_of_interest') ? [$request->field_of_interest] : null,
            ]);
        }

        event(new Registered($student));

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard');
    }
}
