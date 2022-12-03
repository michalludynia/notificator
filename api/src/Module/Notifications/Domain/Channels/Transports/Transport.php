<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels\Transports;

use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;

interface Transport
{
    /** @throws TransportFailedException */
    public function send(Receiver $to, Notification $notification): void;

    public function isAvailable(): bool;

    public function getId(): TransportId;
}
