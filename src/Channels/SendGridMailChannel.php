<?php

namespace Roerjo\LaravelNotificationsSendGridDriver\Channels;

use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Channels\MailChannel;
use Roerjo\LaravelNotificationsSendGridDriver\Messages\SendGridMailMessage;

class SendGridMailChannel extends MailChannel
{
    /**
     * Send the given notification
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Using @toSendGrid instead of the original @toMail
        $message = $notification->toSendGrid($notifiable);

        if (! $notifiable->routeNotificationFor('mail', $notification) &&
            ! $message instanceof Mailable &&
            ! $message instanceof SendGridMailMessage) {
            return;
        }

        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        $this->mailer->send(
            $this->buildView($message),
            array_merge($message->data(), $this->additionalMessageData($notification)),
            $this->messageBuilder($notifiable, $notification, $message)
        );
    }

    /**
     * Build the mail message
     *
     * @param \Illuminate\Mail\Message $mailMessage
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param \Illuminate\Notifications\Messages\MailMessage $message
     * @return void
     */
    protected function buildMessage($mailMessage, $notifiable, $notification, $message)
    {
        // Add the SendGrid options, if available
        if (! empty($message->sendgrid_params)) {
            $mailMessage->embedData(json_encode($message->sendgrid_params), 'sendgrid/x-smtpapi');
        }

        parent::buildMessage($mailMessage, $notifiable, $notifiable, $message);
    }

    /**
     * Get the recipients of the given message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return mixed
     */
    protected function getRecipients($notifiable, $notification, $message)
    {
        // Ability for anonymous SendGrid notifications:
        //     Notification::route('sendgrid', 'email@address.com')
        //         ->notify(new SomeNotification);
        if ($notifiable instanceof AnonymousNotifiable &&
            is_string($recipients = $notifiable->routeNotificationFor('sendgrid', $notification))
        ) {
            $recipients = [$recipients];
        // Otherwise, use the mail driver to pull the email address off the object.
        } elseif (is_string($recipients = $notifiable->routeNotificationFor('mail', $notification))) {
            $recipients = [$recipients];
        }

        return collect($recipients)->mapWithKeys(function ($recipient, $email) {
            return is_numeric($email)
                    ? [$email => (is_string($recipient) ? $recipient : $recipient->email)]
                    : [$email => $recipient];
        })->all();
    }
}
