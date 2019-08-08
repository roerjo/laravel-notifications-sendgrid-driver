<?php

namespace Roerjo\Messages;

use Illuminate\Notifications\Messages\MailMessage;

class SendGridMailMessage extends MailMessage
{
    /**
     * SendGrid options to be attached to the outgoing message
     *
     * @var array
     */
    public $sendgrid_params = [];

    /**
     * Attach SendGrid options to the message
     *
     * @param array $params
     * @return \Roerjo\Messages\SendGridMailMessage $this
     */
    public function sendgrid(array $params)
    {
        $this->sendgrid_params = $params;

        return $this;
    }
}
