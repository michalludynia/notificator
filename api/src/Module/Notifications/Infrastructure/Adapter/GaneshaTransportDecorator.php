<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Ackintosh\Ganesha;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Domain\ValueObject\TransportId;

class GaneshaTransportDecorator implements Transport
{
    public function __construct(
        private readonly Transport $decorated,
        private readonly Ganesha $ganesha
    ) {
    }

    public function send(Recipient $recipient, Notification $notification): void
    {
        try {
            $this->decorated->send($recipient, $notification);
            $this->ganesha->success($this->getId()->getValue());
        } catch (TransportFailedException $e) {
            $this->ganesha->failure($this->getId()->getValue());
            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return $this->ganesha->isAvailable($this->getId()->getValue());
    }

    public function getId(): TransportId
    {
        return $this->decorated->getId();
    }
}
