<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class Recipient
{
    public function __construct(
        public readonly Email $email,
        public readonly Phone $phone
    ) {
    }
}
