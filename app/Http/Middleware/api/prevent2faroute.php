<?php

namespace App\Http\Middleware\api;

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

        $_2fa = auth()->user()->tokenCan('2fa');

        if (($request->fullUrlIs(route('apienable2fa')) && $auth2fa === 0) || ($request->fullUrlIs(route('apiauthenticate')) && (!$_2fa) && $auth2fa === 1)) {
            return $next($request);
        }

        // abort_if(($request->fullUrlIs(route('apienable2fa')) && $auth2fa === 1) || ($request->fullUrlIs(route('apiauthenticate')) && ($_2fa) && $auth2fa === 1), 404);
        abort(404);
    }
}
