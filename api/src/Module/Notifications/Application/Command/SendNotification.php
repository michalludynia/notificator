<?php

declare(strict_types=1);

namespace Notifications\Application\Command;

use Notifications\Application\DTO\NotificationDTO;
use Notifications\Application\DTO\RecipientDTO;

final class SendNotification
{
    public function __construct(
        public readonly RecipientDTO $recipient,
        public readonly NotificationDTO $notification,
    ) {
    }
}
