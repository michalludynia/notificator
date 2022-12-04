<?php

declare(strict_types=1);

namespace Notifications\Test\Business\TestDouble\Repository;

use Notifications\Domain\ValueObject\ChannelId;

class FeaturesFlagsFakeStorage
{
    /** @var array<string, bool> */
    private array $storage = [];

    public function markAsActive(ChannelId $channelId): void
    {
        $this->storage[$channelId->value] = true;
    }

    public function markAsInActive(ChannelId $channelId): void
    {
        $this->storage[$channelId->value] = false;
    }

    public function isActive(ChannelId $channelId): bool
    {
        if (!isset($this->storage[$channelId->value])) {
            throw new \RuntimeException('Requested channel not supported by feature flags');
        }

        return $this->storage[$channelId->value];
    }
}
