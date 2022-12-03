<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class NotificationResult
{
    private function __construct(
        public readonly bool $hasSucceed,
        public readonly ?ChannelId $usedChannel,
        public readonly ?TransportId $usedTransport,
        public readonly ?FailureReason $failureReason,
    ) {
    }

    public static function success(ChannelId $channelId, TransportId $transportId): self
    {
        return new self(true, $channelId, $transportId, null);
    }

    public static function failed(ChannelId $channelId, TransportId $transportId, FailureReason $reason): self
    {
        return new self(false, $channelId, $transportId, $reason);
    }

    public static function notPerformed(): self
    {
        return new self(false, null, null, FailureReason::NONE_OF_PROVIDERS_IS_AVAILABLE);
    }
}
