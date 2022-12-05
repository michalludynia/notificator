<?php

declare(strict_types=1);

namespace Notifications\Application\DTO;

final class NotificationDTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $content
    ) {
    }
}
