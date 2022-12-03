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
        private readonly LoggerInterface $notificationsLogs
    ) {
    }

    public function log(Receiver $receiver, Notification $notification, NotificationResult $notificationResult): void
    {
        if (false === $notificationResult->hasSucceed) {
            $this->notificationsLogs->notice(
                sprintf('[NOTIFICATOR_FAILED] Receiver: %s MessageId: %s Reason: %s',
                    $receiver->email->getValue(),
                    $notification->messageId->getValue(),
                    $notificationResult->failureReason->value ?? ''
                )
            );

            return;
        }

        $this->notificationsLogs->info(
            sprintf(
                '[NOTIFICATOR_SUCCEEDED] Receiver: %s MessageId: %s',
                $receiver->email->getValue(),
                $notification->messageId->getValue(),
            )
        );
    }
}
