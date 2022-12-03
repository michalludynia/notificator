<?php

declare(strict_types=1);

namespace Notifications\Domain\NotificationChannels;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;

interface NotificationChannel
{
    public function sendNotification(Receiver $to, Notification $notification): void;

    public function isTurnedOn(): bool;
}
