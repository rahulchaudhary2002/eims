<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('student/*') || $request->routeIs('student.*')) {
            return route('student.login');
        }

        if ($request->is('admin/*') || $request->routeIs('admin.*')) {
            return route('admin.login');
        }

        if ($request->is('institution/*')) {
            return route('admin.login');
        }

        if ($request->routeIs('website.*')) {
            return route('student.login');
        }

        return route('admin.login');
    }
}
