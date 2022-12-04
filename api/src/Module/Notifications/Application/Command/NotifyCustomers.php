<?php

declare(strict_types=1);

namespace Notifications\Application\Command;

use Notifications\Application\DTO\CustomerDTO;

class NotifyCustomers
{
    public function __construct(
        public readonly string $messageId,
        /** @var CustomerDTO[] $customers */
        public readonly array $customers,
    ) {
    }
}
