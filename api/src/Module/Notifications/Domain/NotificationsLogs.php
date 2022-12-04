<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;

interface NotificationsLogs
{
    public function log(Notification $notification, NotificationResult $notificationResult): void;
}
