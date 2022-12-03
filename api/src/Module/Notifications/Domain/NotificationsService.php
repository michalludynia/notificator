<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\Exception\SendingNotificationFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;

class NotificationsService
{
    public function __construct(
        private readonly NotificatorInterface $notificator
    ) {
    }

    /** @throws SendingNotificationFailedException */
    public function sendNotification(Receiver $receiver, Notification $notification): void
    {
        $result = $this->notificator->notify($receiver, $notification);

        if (false === $result->hasSucceed && null !== $result->failureReason) {
            throw new SendingNotificationFailedException($result->failureReason->value);
        }
    }
}
