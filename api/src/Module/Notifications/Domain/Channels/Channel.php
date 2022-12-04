<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels;

use Notifications\Domain\Exception\ChannelAllTransportsFailedException;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Recipient;

interface Channel
{
    /** @throws ChannelAllTransportsFailedException */
    public function sendNotification(Recipient $recipient, Notification $notification): void;

    public function isActivated(): bool;

    public static function getId(): ChannelId;
}
