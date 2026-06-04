<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveInstitution
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        abort_unless($user, 403);

        $selectedId = (int) session('active_institution_id');

        if ($selectedId) {
            $canAccessSelectedInstitution = $user->is_super_admin
                ? Institution::active()->whereKey($selectedId)->exists()
                : $user->canAccessInstitution($selectedId);

            if ($canAccessSelectedInstitution) {
                return $next($request);
            }

            session()->forget('active_institution_id');

            return redirect()->route('institution.select')
                ->with('error', 'Please select an institution assigned to your account.');
        }

        $activeInstitutions = $user->activeInstitutions()
            ->orderByPivot('is_primary', 'desc')
            ->orderBy('institutions.name')
            ->get();

        if ($activeInstitutions->isEmpty() && $user->is_super_admin) {
            return redirect()->route('institution.select');
        }

        if ($activeInstitutions->count() === 1) {
            session(['active_institution_id' => $activeInstitutions->first()->id]);

            return $next($request);
        }

        return redirect()->route('institution.select');
    }
}
