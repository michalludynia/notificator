<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\NotificationChannels\NotificationChannel;
use Notifications\Domain\ValueObject\FailureReason;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

class Notificator implements NotificatorInterface
{
    /** @param NotificationChannel[] $channels */
    public function __construct(
        private readonly iterable $channels
    ) {
    }

    public function notify(Receiver $receiver, Notification $notification): NotificationResult
    {
        foreach ($this->channels as $channel) {
            if (false === $channel->isActivated()) {
                continue;
            }

            $channel->sendNotification($receiver, $notification);

            return NotificationResult::success();
        }

        return NotificationResult::failed(FailureReason::NONE_OF_PROVIDERS_IS_AVAILABLE);
    }
}
