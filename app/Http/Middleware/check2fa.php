<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        $otp_enabled = auth()->user()['2fa'];

        if ($otp_enabled === 0) {
            return redirect(route('enable2fa'));
        }
        return session('2fa') ? $next($request) : redirect(route('authenticate'));
    }
}
