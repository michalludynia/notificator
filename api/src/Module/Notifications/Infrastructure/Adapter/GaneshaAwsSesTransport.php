<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Ackintosh\Ganesha;
use Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;

class GaneshaAwsSesTransport implements Transport
{
    public function __construct(
        private readonly Transport $decorated,
        private readonly Ganesha $ganesha
    ) {
    }

    public function send(Receiver $to, Notification $notification): void
    {
        try {
            $this->decorated->send($to, $notification);
            $this->ganesha->success($this->getId()->value);
        } catch (\Throwable $e) {
            $this->ganesha->failure($this->getId()->value);
            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return $this->ganesha->isAvailable($this->getId()->value);
    }

    public function getId(): TransportId
    {
        return $this->decorated->getId();
    }
}
