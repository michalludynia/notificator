<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class NotificationResult
{
    private function __construct(
        public readonly bool $hasSucceed,
        public readonly ?string $recipient,
        public readonly ?ChannelId $usedChannel,
        public readonly ?TransportId $usedTransport,
        public readonly ?FailureReason $failureReason,
    ) {
    }

    public static function success(ChannelId $channelId, TransportId $transportId, string $recipient): self
    {
        return new self(true, $recipient, $channelId, $transportId, null);
    }

    public static function failed(ChannelId $channelId, TransportId $transportId, string $recipient, FailureReason $reason): self
    {
        return new self(false, $recipient, $channelId, $transportId, $reason);
    }

    public static function failedAllAvailableProvidersFailed(): self
    {
        return new self(false, null, null, null, FailureReason::ALL_AVAILABLE_PROVIDERS_FAILED);
    }
}
