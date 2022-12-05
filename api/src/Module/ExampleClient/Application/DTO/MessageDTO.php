<?php

declare(strict_types=1);

namespace ExampleClient\Application\DTO;

final class MessageDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $content
    ) {
    }
}
