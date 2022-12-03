<?php

declare(strict_types=1);

namespace Application\Command;

use Notifications\Application\DTO\ReceiverDTO;

class SendNotification
{
    public function __construct(
        public readonly string $messageId,
        public readonly ReceiverDTO $receiverDTO,
        public readonly string $preferredLanguage
    ) {
    }
}
