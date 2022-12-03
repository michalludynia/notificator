<?php

declare(strict_types=1);

namespace Notifications\Domain\Channels;

use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Receiver;

interface Channel
{
    public function sendNotification(Receiver $to, Notification $notification): NotificationResult;

    public function isActivated(): bool;

    public static function getId(): ChannelId;
}
