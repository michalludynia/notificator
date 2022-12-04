<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum ChannelId: string
{
    case EmailChannel = 'email_channel';
    case SmsChannel = 'sms_channel';
}
