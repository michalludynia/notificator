<?php

declare(strict_types=1);

namespace Notifications\Domain\NotificationChannels;

use Notifications\Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
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

    public function sendNotification(Receiver $to, Notification $notification): NotificationResult
    {
        foreach ($this->emailTransports as $emailTransport) {
            if (false === $emailTransport->isAvailable()) {
                continue;
            }

            $emailTransport->send($to, $notification);

            return NotificationResult::success(
                self::getId(),
                $emailTransport->getId(),
            );
        }

        return NotificationResult::notPerformed();
    }

    public function isActivated(): bool
    {
        return $this->activationFlags->isChannelActivated(self::getId());
    }

    public static function getId(): ChannelId
    {
        return ChannelId::fromString(self::CHANNEL_ID);
    }
}
