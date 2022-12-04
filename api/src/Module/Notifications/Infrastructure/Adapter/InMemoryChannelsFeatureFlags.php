<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\ValueObject\ChannelId;

class InMemoryChannelsFeatureFlags implements ChannelsFeatureFlags
{
    private const FLAGS = [
        'email_channel' => true,
        'phone_channel' => true,
    ];

    public function isChannelActivated(ChannelId $channelId): bool
    {
        if (!isset(self::FLAGS[$channelId->getValue()])) {
            throw new \RuntimeException('Channel with requested id not exists');
        }

        return self::FLAGS[$channelId->getValue()];
    }
}
