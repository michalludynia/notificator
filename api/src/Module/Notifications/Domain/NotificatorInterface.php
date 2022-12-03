<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

interface NotificatorInterface
{
    public function notify(Receiver $receiver, Notification $notification): NotificationResult;
}
