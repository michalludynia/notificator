<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AwsSesTransport implements Transport
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
        $email = (new Email())
            ->to($to->email->getValue())
            ->subject($notification->messageTitle)
            ->text($notification->messageContent);

        $this->mailer->send($email);
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getId(): TransportId
    {
        return TransportId::EMAIL_TRANSPORT_AWS_SES;
    }
}
