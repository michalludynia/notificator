<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class MessageId
{
    public function __construct(
        public readonly string $id
    ) {
    }
}