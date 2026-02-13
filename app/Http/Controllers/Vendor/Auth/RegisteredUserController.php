<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Vendor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_name' => ['required', 'string', 'max:255'],
            'institution_type' => ['required', Rule::exists('institution_types', 'id')],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Vendor::class . ',email'],
            'contact_phone' => ['required', 'string', 'regex:/^(?:\+?\d{1,3}[- ]?)?\d{10}$/', 'unique:' . Vendor::class . ',phone'],
            'address' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255', 'url'],
            'established_year' => ['nullable', 'integer', 'digits:4', 'min:1900', 'max:' . date('Y')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $institution = Institution::create([
            'name' => $request->institution_name,
            'institution_type_id' => $request->institution_type,
            'address' => $request->address,
            'website' => $request->website,
            'established_year' => $request->established_year,
        ]);

        $vendor = Vendor::create([
            'name' => $request->contact_name,
            'email' => $request->contact_email,
            'phone' => $request->contact_phone,
            'password' => Hash::make($request->password),
        ]);

        $vendor->institutions()->attach($institution->id, ['is_main' => true]);

        event(new Registered($vendor));

        Auth::guard('vendor')->login($vendor);
        session(['current_institution' => $vendor->institutions->first()]);

        return redirect(route('vendor.dashboard', absolute: false));
    }
}
