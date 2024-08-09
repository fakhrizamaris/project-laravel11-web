<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AksesUser
{
    public function handle($request, Closure $next, $role)
    {
        // Your logic to check user's role
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/');
        }

        return $next($request);
    }
}
