<?php

declare(strict_types=1);

namespace Notifications\Domain;

use Notifications\Domain\Channels\Channel;
use Notifications\Domain\Exception\ChannelAllTransportsFailedException;
use Notifications\Domain\Exception\SendingNotificationFailedException;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;

class Notificator implements NotificatorInterface
{
    /** @param Channel[] $channels */
    public function __construct(
        private readonly iterable $channels
    ) {
    }

    public function notify(Recipient $recipient, Notification $notification): void
    {
        foreach ($this->channels as $channel) {
            if (false === $channel->isActivated()) {
                continue;
            }

            try {
                $channel->sendNotification($recipient, $notification);
            } catch (ChannelAllTransportsFailedException) {
                continue;
            }

            return;
        }

        throw new SendingNotificationFailedException('All transports from available channels failed');
    }
}
