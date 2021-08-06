<?php

namespace App\Http\Controllers;

use App\Http\Requests\Activate2faRequest;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as G2QR;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use PragmaRX\Google2FALaravel\Middleware;

class Google2faController extends Controller
{
    private $g2fa;
    private $key;
    private $secret;

    public function __construct(Google2FA $g2fa)
    {
        $this->g2fa = $g2fa;
    }

    public function enable()
    {
        if (session()->has('2fa_secret')) {
            $this->secret = session('2fa_secret');
        } else {
            $this->secret = $this->g2fa->generateSecretKey();
        }
        session(['2fa_secret' => $this->secret]);
        $qrcode = (new G2QR())->getQRCodeInline('CompanyName', 'UserEmail', $this->secret, 400);

        return view('google2fa.enable2fa')
            ->with([
                'img' => $qrcode,
                'secret' => $this->secret,
            ]);
    }

    public function activate(Activate2faRequest $request)
    {
        $this->secret = session('2fa_secret');
        $this->key = $request->validated()['gotp'];
        $isKeyValid = $this->g2fa->verifyKeyNewer($this->secret, $this->key, null);

        if ($isKeyValid) {
            //update user attribute to use 2fa
            auth()->user()->update([
                '2fa' => true,
                'auth_secret' => $this->secret,
            ]);

            //Login on Successful Activation of 2fa
            (new Authenticator(request()))->login();

            //redirect to requsted route
            return redirect()->intended();
        }
        return redirect()->route('enable2fa');
    }

    public function auth2fa()
    {
        $status=(new Authenticator(request()))->isAuthenticated();
        if(!$status){
            request()->session()->flash('err','Please Enter Valid OTP');
        }
        return redirect()->intended();
    }
}
