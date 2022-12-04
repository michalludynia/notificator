<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;

interface NotificatorInterface
{
    public function notify(Recipient $recipient, Notification $notification): void;
}
