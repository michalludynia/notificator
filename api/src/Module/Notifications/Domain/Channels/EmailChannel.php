<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\ChannelAllTransportsFailedException;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;

class EmailChannel implements Channel
{
    private const CHANNEL_ID = 'email_channel';

    /** @param Transport[] $emailTransports */
    public function __construct(
        private readonly iterable $emailTransports,
        private readonly ChannelsFeatureFlags $activationFlags
    ) {
    }

    public function sendNotification(Recipient $recipient, Notification $notification): void
    {
        foreach ($this->emailTransports as $emailTransport) {
            if (false === $emailTransport->isAvailable()) {
                continue;
            }

            try {
                $emailTransport->send($recipient, $notification);

                return;
            } catch (TransportFailedException) {
                continue;
            }
        }

        throw new ChannelAllTransportsFailedException();
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
