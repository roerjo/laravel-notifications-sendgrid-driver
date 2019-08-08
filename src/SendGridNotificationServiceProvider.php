<?php

namespace Roerjo;

use Roerjo\Channels\SendGridMailChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\NotificationServiceProvider as ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        ChannelManager::extend('sendgrid', function ($app) {
            return $app->make(SendGridMailChannel::class);
        });
    }
}
