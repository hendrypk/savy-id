<?php

namespace App\Mail;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email')
            ->view('mail.verify-email', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }
}