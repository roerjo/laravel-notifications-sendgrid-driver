<?php

namespace Roerjo\LaravelNotificationsSendGridDriver\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\MailChannel;

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
            ! $message instanceof Mailable) {
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
}
