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
    private const TRANSPORT_ID = 'aws_ses_email_transport';

    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
        $email = (new Email())
            ->to($to->email->getValue())
            ->subject('test')
            ->text($notification->localisedContent);

        $this->mailer->send($email);
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getId(): TransportId
    {
        return TransportId::fromString(self::TRANSPORT_ID);
    }
}
