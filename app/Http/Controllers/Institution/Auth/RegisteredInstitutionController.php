<?php

namespace App\Http\Controllers\Institution\Auth;

use App\Events\NewRegistrationNotification;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\ProgramCategory;
use App\Models\User;
use App\Notifications\NewRegistrationAlert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class RegisteredInstitutionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_name'    => ['required', 'string', 'max:255'],
            'contact_name'        => ['required', 'string', 'max:255'],
            'contact_designation' => ['nullable', 'string', 'max:100'],
            'email'               => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'               => ['nullable', 'string', 'max:30'],
            'institution_type'    => ['nullable', 'string', 'max:50'],
            'address'             => ['nullable', 'string', 'max:255'],
            'password'            => ['required', 'confirmed', Password::min(8)],
            'terms'               => ['accepted'],
        ]);

        // Create a pending User account for the institution contact person.
        // A super admin reviews and activates it, then assigns to an institution.
        $user = \App\Models\User::create([
            'name'          => $request->contact_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => Hash::make($request->password),
            'is_active'     => false, // pending approval
        ]);

        // Store registration metadata in the session so it can be picked up after admin review.
        // (In a full implementation this would be a dedicated InstitutionRegistration model.)
        // Notify all platform users in real-time
        $platformUsers = User::where('is_active', true)
            ->where(fn ($q) => $q->where('is_super_admin', true)->orWhere('is_platform_user', true))
            ->get();
        $userUrl = route('admin.users.show', $user->id);
        Notification::send($platformUsers, new NewRegistrationAlert('institution', $user->name, $user->email, $userUrl));
        event(new NewRegistrationNotification('institution', $user->name, $user->email, $user->id, $userUrl));

        session()->flash('institution_registered', true);

        return redirect()->route('register', ['tab' => 'institution'])
            ->with('institution_registered', true);
    }
}
