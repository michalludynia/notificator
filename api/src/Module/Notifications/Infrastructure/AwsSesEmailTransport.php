<?php

declare(strict_types=1);

namespace Notifications\Infrastructure;

use Domain\NotificationChannels\Transports\EmailTransport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AwsSesEmailTransport implements EmailTransport
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
        $email = (new Email())
            ->to($to->email->getValue())
            ->subject('test')
            ->text('Sending emails is fun again!');

        $this->mailer->send($email);
    }

    public function isAvailable(): bool
    {
        return true;
    }
}
