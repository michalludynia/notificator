<?php

declare(strict_types=1);

namespace Notifications\Domain\NotificationChannels;

use Domain\NotificationChannels\Providers\EmailProvider;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Receiver;

class EmailChannel implements NotificationChannel
{
    /** @param EmailProvider[] $emailProviders */
    public function __construct(
        private readonly iterable $emailProviders
    ) {
    }

    public function sendNotification(Receiver $to, Notification $notification): void
    {
        foreach ($this->emailProviders as $emailProvider) {
            if (false === $emailProvider->isAvailable()) {
                continue;
            }

            $emailProvider->send($to, $notification);

            return;
        }

        throw new \RuntimeException('All email providers are down');
    }

    public function isTurnedOn(): bool
    {
        return true;
    }
}
