<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activate2faRequest;
use PragmaRX\Google2FAQRCode\Google2FA;

class TotpController extends Controller
{
    private $secret;
    public function index()
    {
        return view('google2fa.getotp');
    }

    public function auth2fa(Activate2faRequest $request)
    {
        $is_valid=app('VerifyOtp',['otp'=>$request->validated()['gotp']]);

        if ($is_valid) {
            session(['2fa' => true]);
            return redirect()->intended();
        }
        return redirect()->back();
    }

    public function enable2fa()
    {
        $google2fa = new Google2FA();
        if (session()->has('2fa_secret')) {
            $this->secret = session('2fa_secret');
        } else {
            $this->secret = $google2fa->generateSecretKey();
            session(['2fa_secret' => $this->secret]);
        }
        $inlineUrl = $google2fa->getQRCodeInline(
            "CompanyName",
            "CompanyEmail",
            $this->secret,
            400
        );

        return view('google2fa.enable2fa')->with(['img' => $inlineUrl, 'secret' => $this->secret]);
    }

    public function activate2fa(Activate2faRequest $request)
    {
        $is_valid=app('VerifyOtp',['otp'=>$request->validated()['gotp'],'secret'=>session('2fa_secret')]);

        if ($is_valid) {

            auth()->user()->update([
                '2fa' => true,
                'auth_secret' => session('2fa_secret'),
            ]);
            session(['2fa' => true]);

            return redirect()->intended();
        }
        return redirect()->back();
    }
}
