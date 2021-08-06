<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class check2fa
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
        $is_2fa = auth()->user()['2fa'];
        $prevent_url = ($request->fullUrlIs(route('enable2fa')) || $request->fullUrlIs(route('activate2fa')));

        if ($prevent_url && (!$is_2fa)) {
            return $next($request);
        }

        if ($prevent_url || ($request->fullUrlIs(route('authenticate2fa')) && !$is_2fa)) {
            return abort(404);
        }

        if ($is_2fa === 0) {
            return redirect()->route('enable2fa');
        }
        $authenticator = app(Authenticator::class)->boot($request);

        if ($authenticator->isAuthenticated()) {
            return $next($request);
        }
        // return redirect('/ccc');

        return $authenticator->makeRequestOneTimePasswordResponse();
    }
}
