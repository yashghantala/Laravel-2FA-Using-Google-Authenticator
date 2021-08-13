<?php

namespace App\Http\Middleware\api;

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
            return response()->json(['action' => 'enable2fa', 'url' => route('apienable2fa')], 302);
        }

        return auth()->user()->tokenCan('2fa') ? $next($request) : response()->json(['action' => 'verify2fa', 'url' => route('apiauthenticate')], 302);
    }
}
