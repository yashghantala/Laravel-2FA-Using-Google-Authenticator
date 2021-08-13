<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activate2faRequest;
use App\Http\Requests\api\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use PragmaRX\Google2FAQRCode\Google2FA;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->pass, $user->password)) {
            return response()->json('Invalid Credentials', 422);
        }
        $user->tokens()->delete();
        if ($user['2fa'] === 0) {
            $secret = 'secret:' . (new Google2FA())->generateSecretKey();
            return response()->json(['_token' => $user->createToken($user->id, [$secret])->plainTextToken, 'action' => 'enable2fa', 'url' => route('apienable2fa')]);
        }
        return response()->json(['_token' => $user->createToken($user->id, [])->plainTextToken, 'action' => 'verify2fa', 'url' => route('apiauthenticate')]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json('ok');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'pass' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->pass),
        ]);

        $secret = 'secret:' . (new Google2FA())->generateSecretKey();
        return response()->json(['_token' => $user->createToken($user->id, [$secret])->plainTextToken, 'action' => 'enable2fa', 'url' => route('apienable2fa')]);
    }

    public function auth2fa(Activate2faRequest $request)
    {
        $is_valid = app('VerifyOtp', ['otp' => $request->validated()['gotp']]);

        if ($is_valid) {
            auth()->user()->currentAccessToken()->delete();
            return response()->json(['_token' => auth()->user()->createToken(auth()->user()->id, ['2fa'])->plainTextToken, 200]);
        }
        return response()->json('Invalid Credentials', 422);
    }

    public function get2fa()
    {
        $secret = explode(':', auth()->user()->currentAccessToken()->abilities[0])[1];
        $inlineUrl = (new Google2FA())->getQRCodeInline(
            "CompanyName",
            "CompanyEmail",
            $secret,
            400
        );

        return response()->json(['img' => $inlineUrl, 'secret' => $secret, 'action' => 'enable2faOTP', 'url' => route('apienable2fa')]);
    }

    public function activate2fa(Activate2faRequest $request)
    {
        $secret = explode(':', auth()->user()->currentAccessToken()->abilities[0])[1];
        $is_valid = app('VerifyOtp', ['otp' => $request->validated()['gotp'], 'secret' => $secret]);

        if ($is_valid) {

            auth()->user()->update([
                '2fa' => true,
                'auth_secret' => $secret,
            ]);

            auth()->user()->currentAccessToken()->delete();
            return response()->json(['_token' => auth()->user()->createToken(auth()->user()->id, ['2fa'])->plainTextToken, 'action' => 'home', 'url' => route('dashboard')]);
        }
        return response()->json('Invalid Credentials', 422);
    }
}
