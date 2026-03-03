<?php

namespace App\Mail;

use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MailtrapApiTransport extends AbstractTransport
{
protected function doSend(SentMessage $message): void
{
    $email = MessageConverter::toEmail($message->getOriginalMessage());

    $mailtrapEmail = (new MailtrapEmail())
        ->from(new Address(
            $email->getFrom()[0]->getAddress(),
            $email->getFrom()[0]->getName()
        ))
        ->subject($email->getSubject());

    // ✅ WAJIB ADA INI
    $html = $email->getHtmlBody();
    $text = $email->getTextBody();

    if ($html) {
        $mailtrapEmail->html($html);
    }

    if ($text) {
        $mailtrapEmail->text($text);
    }

    // Fallback biar tidak pernah kosong
    if (!$html && !$text) {
        $mailtrapEmail->text('Email content missing.');
    }

    foreach ($email->getTo() as $recipient) {
        $mailtrapEmail->to(
            new Address($recipient->getAddress(), $recipient->getName())
        );
    }

    MailtrapClient::initSendingEmails(
        apiKey: config('services.mailtrap.token'),
        isSandbox: true,
        inboxId: config('services.mailtrap.inbox_id')
    )->send($mailtrapEmail);
}

    public function __toString(): string
    {
        return 'mailtrap-api';
    }
}