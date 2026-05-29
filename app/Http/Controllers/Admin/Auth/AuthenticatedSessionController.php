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

        $user = auth('web')->user();

        // Super admins go to admin dashboard; institution users go to institution dashboard
        if ($user->is_super_admin) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Set current institution for institution users
        $firstInstitution = $user->activeInstitutions()->first();
        if ($firstInstitution) {
            session([
                'current_institution_id' => $firstInstitution->id,
                'active_institution_id' => $firstInstitution->id,
            ]);
        }

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
