<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class prevent2faroute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $auth2fa = auth()->user()['2fa'];

        if (($request->fullUrlIs(route('enable2fa')) && $auth2fa === 0) || ($request->fullUrlIs(route('authenticate')) && (!session('2fa')) && $auth2fa === 1)) {
            return $next($request);
        }

        abort_if(($request->fullUrlIs(route('enable2fa')) && $auth2fa === 1) || ($request->fullUrlIs(route('authenticate')) && (session('2fa')) && $auth2fa === 1), 404);

        return redirect()->back();
    }
}
