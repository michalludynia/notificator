<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

class LoggableNotificatorDecorator implements NotificatorInterface
{
    public function __construct(
        private readonly NotificatorInterface $decorated,
        private readonly NotificationsLogs $notificationsLogs
    ) {
    }

    public function notify(Receiver $receiver, Notification $notification): NotificationResult
    {
        $result = $this->decorated->notify($receiver, $notification);
        $this->notificationsLogs->log($receiver, $notification, $result);

        return $result;
    }
}
