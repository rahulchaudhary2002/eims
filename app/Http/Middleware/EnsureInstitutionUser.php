<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstitutionUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        abort_unless($user, 403, 'Please sign in to access the institution dashboard.');

        if ($request->user('student')) {
            abort(403, 'Students cannot access the institution dashboard.');
        }

        $hasInstitution = $user->activeInstitutions()->exists();

        abort_unless(
            $hasInstitution || $user->is_super_admin,
            403,
            'No active institution assignment was found for your account.'
        );

        return $next($request);
    }
}
