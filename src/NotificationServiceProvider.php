<?php

namespace Nasution\ZenzivaSms;

use Nasution\ZenzivaSms\Client as Sms;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Nasution\ZenzivaSms\Client', function () {
            return new Sms(
                config('services.zenziva.userkey'),
                config('services.zenziva.passkey')
            );
        });
    }
}
