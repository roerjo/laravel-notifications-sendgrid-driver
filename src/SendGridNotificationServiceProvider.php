<?php

namespace Roerjo\LaravelNotificationsSendGridDriver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Roerjo\LaravelNotificationsSendGridDriver\Channels\SendGridMailChannel;

class SendGridNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Notification::extend('sendgrid', function ($app) {
            return $app->make(SendGridMailChannel::class);
        });
    }
}
