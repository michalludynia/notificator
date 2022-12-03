<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Notifications\Domain\NotificationsLogs;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;
use Psr\Log\LoggerInterface;

class MonologNotificationsLogs implements NotificationsLogs
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function log(Receiver $receiver, Notification $notification, NotificationResult $notificationResult): void
    {
        if (false === $notificationResult->hasSucceed) {
            $this->logger->notice(
                sprintf('[NOTIFICATOR_FAILED] Receiver: %s MessageTitle: %s Reason: %s',
                    $receiver->email->getValue(),
                    $notification->messageTitle,
                    $notificationResult->failureReason->value ?? ''
                )
            );

            return;
        }

        $this->logger->info(
            sprintf(
                '[NOTIFICATOR_SUCCEEDED] Receiver: %s MessageTitle: %s',
                $receiver->email->getValue(),
                $notification->messageTitle,
            )
        );
    }
}
