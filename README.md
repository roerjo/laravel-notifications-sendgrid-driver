Laravel Notifications SendGrid Driver
====

A Notification Driver with support for Sendgrid Web API.

# Requirements

This package depends upon https://github.com/s-ichikawa/laravel-sendgrid-driver. Ensure that you have that package installed before using this package.


# Install (Laravel)

```bash
composer require roerjo/laravel-notifications-sendgrid-driver:"^1.0"
```

OR

Add the package to your composer.json and run composer update.
```json
"require": {
    "roerjo/laravel-notifications-sendgrid-driver": "^1.0"
},
```


# Usage

This package extends the functionality of the MailMessage class.
It allows the addition of SendGrid API parameters in the same way that https://github.com/s-ichikawa/laravel-sendgrid-driver allows them to be added to the Mailable classes.

The `sendgrid` driver will need be utilized in the @via method of the Notification class:
```php
/**
 * Get the notification's delivery channels.
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function via($notifiable)
{
    return ['sendgrid'];
}
```

Then, a `toSendGrid` method call can be used to generate the SendGridMailMessage:
```php
/**
 * Get the mail representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return \Roerjo\LaravelNotificationsSendGridDriver\Messages\SendGridMailMessage
 */
public function toSendGrid($notifiable)
{
    $accountId = $this->profile->account()->first()->id;
    $channel = config("channels.{$this->profile->channel}.title");

    return (new SendGridMailMessage)
        ->sendgrid([
            'asm' => [
                'group_id' => config('services.sendgrid.unsubscribe_groups.external')
            ],
        ])
        ->error()
        ->subject("We Need To Re-Authenticate Your {$channel} Profile")
        ->line("The token for your {$channel} profile is no longer valid.")
        ->action(
            "Authenticate {$channel}",
            url("accounts/{$accountId}/profiles")
        )
        ->line('Thank you for helping us help you!');
}
```

Be sure to import SendGridMailMessage if not using the fully qualified namespace in `toSendGrid`:
```php
use \Roerjo\LaravelNotificationsSendGridDriver\Messages\SendGridMailMessage;
```
