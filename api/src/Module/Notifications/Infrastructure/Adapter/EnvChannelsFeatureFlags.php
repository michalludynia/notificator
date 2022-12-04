<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\ValueObject\ChannelId;

class EnvChannelsFeatureFlags implements ChannelsFeatureFlags
{
    public function __construct(
        private readonly bool $useEmailChannel,
        private readonly bool $useSmsChannel
    ) {
    }

    public function isChannelActivated(ChannelId $channelId): bool
    {
        return match ($channelId) {
            ChannelId::EmailChannel => $this->useEmailChannel,
            ChannelId::SmsChannel => $this->useSmsChannel,
        };
    }
}
