<?php

declare(strict_types=1);

namespace Notifications\Application\DTO;

class MessageContentDTO
{
    public function __construct(
        public readonly string $content
    ) {
    }
}
