<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class Notification
{
    public function __construct(
        public readonly MessageId $messageId,
        public readonly string $localisedContent
    ) {
    }
}
