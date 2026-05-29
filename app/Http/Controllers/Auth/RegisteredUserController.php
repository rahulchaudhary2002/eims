<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EducationField;
use App\Models\Level;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $educationFields = Schema::hasTable('education_fields')
            ? EducationField::query()->orderBy('name')->get(['name', 'slug'])
            : collect();
        $educationLevels = Level::query()->orderBy('order')->get(['id', 'name']);
        return view('auth.register', compact('educationFields', 'educationLevels'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'string', 'regex:/^(?:\+?\d{1,3}[- ]?)?\d{10}$/', 'unique:' . User::class],
            'dob' => ['required', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'education_level_id' => ['required', 'string', 'max:255', Rule::exists('levels', 'id')],
            'field_of_interest' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('education_fields', 'slug'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'address' => $request->address,
            'education_level_id' => $request->education_level_id,
            'field_of_interest' => $request->field_of_interest,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
