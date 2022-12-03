<?php

declare(strict_types=1);

namespace Notifications\Domain\NotificationChannels;

use Notifications\Domain\ValueObject\ChannelId;

interface ChannelsActivationFlags
{
    public function isChannelActivated(ChannelId $channelId): bool;
}
