<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OTPHP\TOTP;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('VerifyOtp', function ($service,$param) {
            $totp = TOTP::create($param['secret']??auth()->user()['auth_secret']);
            $is_valid = $totp->verify($param['otp'], null, $param['window']??config('g2fa.window'));

            return $is_valid;
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
