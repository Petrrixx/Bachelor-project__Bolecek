<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('access_token')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
