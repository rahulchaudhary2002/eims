<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        if ($request->is('institution/*')) {
            return route('vendor.login');
        }

        return route('login');
    }
}
