<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = auth('web')->user();

        // Always set institution session if the user has any active institution assignments
        $firstInstitution = $user->activeInstitutions()->first();
        if ($firstInstitution) {
            session([
                'current_institution_id' => $firstInstitution->id,
                'active_institution_id'  => $firstInstitution->id,
            ]);
        }

        // Platform users (including super admins) go to admin dashboard
        // They can still navigate to institution dashboard if they have assignments
        if ($user->is_super_admin || $user->is_platform_user) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Institution-only users go to institution dashboard
        return redirect()->intended(route('institution.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->forget(['current_institution_id', 'active_institution_id']);

        return redirect()->route('admin.login');
    }
}
