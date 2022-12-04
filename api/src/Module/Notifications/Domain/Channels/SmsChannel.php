<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\ChannelAllTransportsFailedException;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;

class SmsChannel implements Channel
{
    private const CHANNEL_ID = 'sms_channel';

    /** @param Transport[] $smsTransports */
    public function __construct(
        private readonly iterable $smsTransports,
        private readonly ChannelsFeatureFlags $activationFlags
    ) {
    }

    public function sendNotification(Recipient $recipient, Notification $notification): void
    {
        foreach ($this->smsTransports as $smsTransport) {
            if (false === $smsTransport->isAvailable()) {
                continue;
            }

            try {
                $smsTransport->send($recipient, $notification);

                return;
            } catch (TransportFailedException) {
                continue;
            }
        }

        throw new ChannelAllTransportsFailedException();
    }

    public function isActivated(): bool
    {
        return $this->activationFlags->isChannelActivated(self::getId());
    }

    public static function getId(): ChannelId
    {
        return ChannelId::fromString(self::CHANNEL_ID);
    }
}
