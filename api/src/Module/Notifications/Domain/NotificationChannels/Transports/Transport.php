<?php

declare(strict_types=1);

namespace Domain\NotificationChannels\Transports;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;

interface Transport
{
    public function send(Receiver $to, Notification $notification): void;

    public function isAvailable(): bool;

    public function getId(): TransportId;
}
