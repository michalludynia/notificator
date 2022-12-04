<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\NotificationsLogs;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Psr\Log\LoggerInterface;

class MonologNotificationsLogs implements NotificationsLogs
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function log(Notification $notification, NotificationResult $notificationResult): void
    {
        if (false === $notificationResult->hasSucceed) {
            $this->logger->notice(
                sprintf('[NOTIFICATOR_FAILED] Receiver: %s MessageTitle: "%s" Reason: "%s" Channel: "%s" Transport: "%s"',
                    $notificationResult->recipient,
                    $notification->messageTitle,
                    $notificationResult->failureReason->value ?? '',
                    $notificationResult->usedChannel?->getValue(),
                    $notificationResult->usedTransport?->getValue(),
                )
            );

            return;
        }

        $this->logger->info(
            sprintf(
                '[NOTIFICATOR_SUCCEEDED] Receiver: "%s" MessageTitle: "%s" Channel: "%s" Transport: "%s"',
                $notificationResult->recipient,
                $notification->messageTitle,
                $notificationResult->usedChannel?->getValue(),
                $notificationResult->usedTransport?->getValue(),
            )
        );
    }
}
