<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Domain\ValueObject\TransportId;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AwsSesTransport implements Transport
{
    private const TRANSPORT_ID = 'email_transport_aws_ses';

    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function send(Recipient $recipient, Notification $notification): void
    {
        try {
            $email = (new Email())
                ->to($recipient->email->getValue())
                ->subject($notification->messageTitle)
                ->text($notification->messageContent);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            throw new TransportFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }
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
