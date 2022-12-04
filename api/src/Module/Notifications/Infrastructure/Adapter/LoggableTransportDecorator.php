<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Domain\ValueObject\TransportId;
use Psr\Log\LoggerInterface;

class LoggableTransportDecorator implements Transport
{
    public function __construct(
        private readonly Transport $decorated,
        private readonly LoggerInterface $logger
    ) {
    }

    public function send(Recipient $recipient, Notification $notification): void
    {
        try {
            $this->decorated->send($recipient, $notification);

            $this->logger->info(
                sprintf(
                    '[NOTIFICATOR_SUCCEEDED] Receiver: "%s/%s" MessageTitle: "%s" Transport: "%s"',
                    $recipient->email->getValue(),
                    $recipient->phone->getValue(),
                    $notification->messageTitle,
                    $this->decorated->getId()->getValue(),
                )
            );
        } catch (TransportFailedException $e) {
            $this->logger->notice(
                sprintf('[NOTIFICATOR_FAILED] Receiver: "%s/%s" MessageTitle: "%s" Error: "%s" Transport: "%s"',
                    $recipient->email->getValue(),
                    $recipient->phone->getValue(),
                    $notification->messageTitle,
                    $e->getMessage(),
                    $this->decorated->getId()->getValue(),
                )
            );

            throw $e;
        }
    }

    public function isAvailable(): bool
    {
        return $this->decorated->isAvailable();
    }

    public function getId(): TransportId
    {
        return $this->decorated->getId();
    }
}
