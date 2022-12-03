<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Client;

class Response
{
    public function __construct(
        public readonly array $body
    ) {
    }
}
