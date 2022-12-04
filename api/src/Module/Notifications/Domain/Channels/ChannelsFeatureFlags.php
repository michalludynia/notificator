<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels;

use Notifications\Domain\ValueObject\ChannelId;

interface ChannelsFeatureFlags
{
    public function isChannelActivated(ChannelId $channelId): bool;
}
