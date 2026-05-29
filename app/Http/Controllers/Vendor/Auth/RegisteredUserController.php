<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_name'     => ['required', 'string', 'max:255'],
            'institution_type'     => ['required', Rule::exists('institution_types', 'slug')],
            'institution_category' => ['nullable', Rule::exists('institution_categories', 'id')],
            'contact_name'         => ['required', 'string', 'max:255'],
            'contact_email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'contact_phone'        => ['required', 'string', 'regex:/^(?:\+?\d{1,3}[- ]?)?\d{10}$/', 'unique:' . User::class . ',phone'],
            'address'              => ['nullable', 'string', 'max:255'],
            'website'              => ['nullable', 'string', 'max:255', 'url'],
            'established_year'     => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'password'             => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $institution = Institution::create([
            'name'                  => $request->institution_name,
            'slug'                  => Str::slug($request->institution_name) . '-' . Str::lower(Str::random(6)),
            'type'                  => $request->institution_type,
            'address'               => $request->address,
            'website'               => $request->website,
            'established_year'      => $request->established_year,
            'status'                => 'pending',
        ]);

        $user = User::create([
            'name'     => $request->contact_name,
            'email'    => $request->contact_email,
            'phone'    => $request->contact_phone,
            'password' => Hash::make($request->password),
        ]);

        $user->institutions()->attach($institution->id, [
            'role_name' => 'owner',
            'position' => 'Owner',
            'is_primary' => true,
            'is_active' => true,
            'joined_at' => now(),
        ]);

        event(new Registered($user));

        Auth::guard('web')->login($user);

        session(['current_institution_id' => $institution->id]);

        return redirect(route('vendor.dashboard', absolute: false));
    }
}

