<?php

declare(strict_types=1);

namespace Notifications\Application\DTO;

final class RecipientDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $phone,
    ) {
    }
}
