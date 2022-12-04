<?php

declare(strict_types=1);

namespace Notifications\Test\Business\TestDouble;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Test\Business\TestDouble\Repository\FeaturesFlagsFakeStorage;

class FakeChannelsFeaturesFlags implements ChannelsFeatureFlags
{
    public function __construct(
        private readonly FeaturesFlagsFakeStorage $featuresFlagsFakeStorage
    ) {
    }

    public function isChannelActivated(ChannelId $channelId): bool
    {
        return $this->featuresFlagsFakeStorage->isActive($channelId);
    }
}
