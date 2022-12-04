<?php

declare(strict_types=1);

namespace Notifications\Test\Business\TestDouble\Spy;

class ChannelUsageSpy
{
    private int $emailChannelUsage = 0;
    private int $smsChannelUsage = 0;

    public function registerEmailChannelUsage(): void
    {
        ++$this->emailChannelUsage;
    }

    public function registerSmsChannelUsage(): void
    {
        ++$this->smsChannelUsage;
    }

    public function getEmailChannelUsage(): int
    {
        return $this->emailChannelUsage;
    }

    public function getSmsChannelUsage(): int
    {
        return $this->smsChannelUsage;
    }
}
