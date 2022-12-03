<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class Notification
{
    public function __construct(
        public readonly Receiver $receiver,
        public readonly MessageId $messageId
    ) {
    }
}
