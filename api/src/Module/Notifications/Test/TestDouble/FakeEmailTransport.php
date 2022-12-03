<?php

declare(strict_types=1);

namespace Notifications\Test\TestDouble;

use Notifications\Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;

class FakeEmailTransport implements Transport
{
    public function __construct(
        private readonly bool $isAvailable
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getId(): TransportId
    {
        return TransportId::EMAIL_TRANSPORT_AWS_SES;
    }
}