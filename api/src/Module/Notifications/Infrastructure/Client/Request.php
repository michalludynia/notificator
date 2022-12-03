<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Client;

interface Request
{
    public function getPath(): string;

    public function getMethod(): string;

    public function getBody(): array;

    public function getContext(): string;
}
