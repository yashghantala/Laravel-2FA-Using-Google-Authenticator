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
        $urls = ($request->fullUrlIs(route('enable2fa')) || ($request->fullUrlIs(route('activate2fa'))));

        if ($urls && $auth2fa === 1) {
            abort(404);
        }
        if ($urls && $auth2fa===0) {
            return $next($request);
        }

        if ($request->fullUrlIs(route('authenticate')) && (!session('2fa')) && $auth2fa === 1) {
            return $next($request);
        }

        return redirect()->back();
    }
}
