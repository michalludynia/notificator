<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;
use Symfony\Component\Notifier\Exception\ExceptionInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Notification\Notification as NotificationSymfony;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class TwilioSmsTransport implements Transport
{
    private const TRANSPORT_ID = 'phone_transport_twilio';

    public function __construct(
        private readonly NotifierInterface $notifier
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
        $symfonyNotification = (new NotificationSymfony(
            $notification->messageTitle, ['sms']
        ))->content($notification->messageContent);

        try {
            $this->notifier->send(
                $symfonyNotification,
                new Recipient(
                    $to->email->getValue(),
                    $to->phone->getValue()
                )
            );
        } catch (\Exception $e) {
            throw new TransportFailedException($e->getMessage(), $e->getCode(), $e);
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
