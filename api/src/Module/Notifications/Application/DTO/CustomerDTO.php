<?php

declare(strict_types=1);

namespace Notifications\Application\DTO;

class CustomerDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $phone,
        public readonly string $preferredLanguage,
    ) {
    }
}
