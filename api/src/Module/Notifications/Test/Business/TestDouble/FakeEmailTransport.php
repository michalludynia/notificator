<?php

declare(strict_types=1);

namespace Notifications\Test\Business\TestDouble;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Domain\ValueObject\TransportId;
use Notifications\Test\Business\TestDouble\Spy\ChannelUsageSpy;

class FakeEmailTransport implements Transport
{
    public function __construct(
      private readonly ChannelUsageSpy $channelUsageSpy
    ) {
    }

    public function send(Recipient $recipient, Notification $notification): void
    {
        $this->channelUsageSpy->registerEmailChannelUsage();
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getId(): TransportId
    {
        return TransportId::fromString('fake_email_transport');
    }
}
