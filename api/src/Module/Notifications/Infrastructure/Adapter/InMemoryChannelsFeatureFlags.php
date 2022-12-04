<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\Channels\SmsChannel;
use Notifications\Domain\ValueObject\ChannelId;

class InMemoryChannelsFeatureFlags implements ChannelsFeatureFlags
{
    public function isChannelActivated(ChannelId $channelId): bool
    {
        $storage = self::flags();

        if (!isset($storage[$channelId->getValue()])) {
            throw new \RuntimeException('Channel with requested id not exists');
        }

        return self::flags()[$channelId->getValue()];
    }

    /** @return array<string,bool> */
    private static function flags(): array
    {
        return [
            EmailChannel::getId()->getValue() => true,
            SmsChannel::getId()->getValue() => true,
        ];
    }
}
