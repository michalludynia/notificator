<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class NotificationResult
{
    private function __construct(
        public readonly bool $hasSucceed,
        public readonly ?FailureReason $failureReason,
    ) {
    }

    public static function success(): self {
        return new self(true, null);
    }

    public static function failed(FailureReason $reason): self {
        return new self(false, $reason);
    }
}