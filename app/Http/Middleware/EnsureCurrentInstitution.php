<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCurrentInstitution
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('web')->user();

        if (! $user) {
            return $next($request);
        }

        // Super admins can access any institution without restriction
        if ($user->is_super_admin) {
            // Auto-select first institution if none selected and a specific one is needed
            if (! session()->has('current_institution_id')) {
                $first = Institution::first();
                if ($first) {
                    session(['current_institution_id' => $first->id]);
                }
            }
            return $next($request);
        }

        // For normal web users, validate current_institution_id belongs to them
        $institutionId = session('current_institution_id');

        $activeInstitutions = $user->activeInstitutions()->get();

        if ($activeInstitutions->isEmpty()) {
            auth('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'No active institution assigned to your account.']);
        }

        // If no institution selected yet, auto-select the first active one
        if (! $institutionId) {
            session(['current_institution_id' => $activeInstitutions->first()->id]);
            return $next($request);
        }

        // Validate the selected institution belongs to this user
        $valid = $activeInstitutions->pluck('id')->contains((int) $institutionId);

        if (! $valid) {
            // Reset to first valid institution
            session(['current_institution_id' => $activeInstitutions->first()->id]);
        }

        return $next($request);
    }
}
