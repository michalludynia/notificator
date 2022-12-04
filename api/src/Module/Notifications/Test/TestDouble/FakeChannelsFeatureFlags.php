<?php

declare(strict_types=1);

namespace Notifications\Test\TestDouble;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\ValueObject\ChannelId;

class FakeChannelsFeatureFlags implements ChannelsFeatureFlags
{
    public function __construct(
        /** @var ChannelId[] $activeChannelsStorage */
        private readonly array $activeChannelsStorage = []
    ) {
    }

    public function isChannelActivated(ChannelId $channelId): bool
    {
        return \in_array($channelId->getValue(), array_map(static fn (ChannelId $channelId) => $channelId->getValue(), $this->activeChannelsStorage), true);
    }
}