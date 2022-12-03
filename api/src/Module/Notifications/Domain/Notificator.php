<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\NotificationChannels\NotificationChannel;
use Notifications\Domain\ValueObject\FailureReason;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

class Notificator
{
    /** @param NotificationChannel[] $channels */
    public function __construct(
        private readonly array $channels
    ) {
    }

    public function notify(Receiver $receiver, Notification $notification): NotificationResult
    {
        foreach ($this->channels as $channel) {
            if (false === $channel->isTurnedOn()) {
                continue;
            }

            $channel->sendNotification($receiver, $notification);

            return NotificationResult::success();
        }

        return NotificationResult::failed(FailureReason::ALL_PROVIDERS_ARE_DOWN);
    }
}
