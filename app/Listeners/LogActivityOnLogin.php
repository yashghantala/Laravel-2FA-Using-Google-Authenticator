<?php

namespace App\Listeners;

use App\Models\loginActivity;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogActivityOnLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        loginActivity::create([
            'user_id'=>auth()->id(),
            'user_ip'=>request()->ip(),
            'user_agent'=>filter_var(request()->userAgent(),FILTER_SANITIZE_STRING),
        ]);
    }
}
