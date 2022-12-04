<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\Channels\Channel;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

class Notificator implements NotificatorInterface
{
    /** @param Channel[] $channels */
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

            $result = $channel->sendNotification($receiver, $notification);

            if (false === $result->hasSucceed) {
                continue;
            }

            return $result;
        }

        return NotificationResult::failedAllAvailableProvidersFailed();
    }
}
