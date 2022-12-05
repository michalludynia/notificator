<?php

declare(strict_types=1);

namespace ExampleClient\Application\DTO;

final class CustomerDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $phone,
        public readonly string $preferredLanguage,
    ) {
    }
}
