<?php

namespace Roerjo\LaravelNotificationsSendGridDriver;

use Illuminate\Notifications\ChannelManager;
use Roerjo\LaravelNotificationsSendGridDriver\Channels\SendGridMailChannel;
use Illuminate\Notifications\NotificationServiceProvider as ServiceProvider;

class SendGridNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        ChannelManager::extend('sendgrid', function ($app) {
            return $app->make(SendGridMailChannel::class);
        });
    }
}
