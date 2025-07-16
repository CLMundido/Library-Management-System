<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle($request, Closure $next)
    {
        if (
            Auth::check() &&
            Auth::user()->force_password_change &&
            !$request->routeIs('profile.show') &&
            !$request->routeIs('logout') &&
            !$request->routeIs('password.update') &&
            !$request->is('user/profile-information') &&
            !$request->is('user/password') &&
            !$request->ajax()
        ) {
            return redirect()->route('profile.show')->with('must_change_password', true);
        }

        return $next($request);
    }
}
