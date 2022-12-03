<?php

declare(strict_types=1);

namespace Notifications\Infrastructure;

use Notifications\Domain\NotificationChannels\ChannelsActivationFlags;
use Notifications\Domain\ValueObject\ChannelId;

class InMemoryChannelsActivationFlags implements ChannelsActivationFlags
{
    private const FLAGS = [
        'email_channel' => true,
        'phone_channel"' => false,
    ];

    public function isChannelActivated(ChannelId $channelId): bool
    {
        if (!isset(self::FLAGS[$channelId->getValue()])) {
            throw new \RuntimeException('Channel with requested id not exists');
        }

        return self::FLAGS[$channelId->getValue()];
    }
}
