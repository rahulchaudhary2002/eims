<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('web');

        abort_unless($user && $user->is_super_admin, 403, 'You do not have access to the admin dashboard.');

        return $next($request);
    }
}
