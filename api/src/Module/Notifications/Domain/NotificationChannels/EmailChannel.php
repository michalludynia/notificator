<?php

declare(strict_types=1);

namespace Notifications\Domain\NotificationChannels;

use Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;

class EmailChannel implements NotificationChannel
{
    private const CHANNEL_ID = 'email_channel';

    /** @param Transport[] $emailTransports */
    public function __construct(
        private readonly iterable $emailTransports,
        private readonly ChannelsActivationFlags $activationFlags
    ) {
    }

    public function sendNotification(Receiver $to, Notification $notification): void
    {
        foreach ($this->emailTransports as $emailTransport) {
            if (false === $emailTransport->isAvailable()) {
                continue;
            }

            $emailTransport->send($to, $notification);

            return;
        }

        throw new \RuntimeException('All email providers are down');
    }

    public function isActivated(): bool
    {
        return $this->activationFlags->isChannelActivated($this->getId());
    }

    public function getId(): ChannelId
    {
        return ChannelId::fromString(self::CHANNEL_ID);
    }
}
