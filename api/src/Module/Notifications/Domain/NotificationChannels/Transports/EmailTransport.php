<?php

declare(strict_types=1);

namespace Domain\NotificationChannels\Transports;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;

interface EmailTransport
{
    public function send(Receiver $to, Notification $notification): void;

    public function isAvailable(): bool;
}
