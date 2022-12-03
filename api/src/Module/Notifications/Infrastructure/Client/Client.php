<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Client;

interface Client
{
    public function send(Request $request): Response;
}
