<?php

declare(strict_types=1);

namespace Notifications\Test\TestDouble;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;

class FakePhoneTransport implements Transport
{
    private const TRANSPORT_ID = 'FAKE_PHONE_TRANSPORT_ID';

    public function __construct(
        private readonly bool $isAvailable,
    ) {
    }

    public function send(
        Receiver $to,
        Notification $notification
    ): void {
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getId(): TransportId
    {
        return TransportId::fromString(self::TRANSPORT_ID);
    }
}
