<?php

declare(strict_types=1);

namespace ExampleClient\Application\Command;

use ExampleClient\Application\DTO\CustomerDTO;

class NotifyCustomers
{
    public function __construct(
        public readonly string $messageId,
        /** @var CustomerDTO[] $customers */
        public readonly array $customers,
    ) {
    }
}
