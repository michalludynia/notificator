<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Infrastructure\Adapter;

use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Infrastructure\Adapter\EnvChannelsFeatureFlags;
use PHPUnit\Framework\TestCase;

class EnvChannelsFeatureFlagsTest extends TestCase
{
    /** @test */
    public function channelActivationIsRecognizedCorrectlyBasedOnEnvVariables(): void
    {
        $featureFlags = new EnvChannelsFeatureFlags(true, true);
        $this->assertTrue($featureFlags->isChannelActivated(ChannelId::EmailChannel));
        $this->assertTrue($featureFlags->isChannelActivated(ChannelId::SmsChannel));

        $featureFlags = new EnvChannelsFeatureFlags(false, false);
        $this->assertFalse($featureFlags->isChannelActivated(ChannelId::EmailChannel));
        $this->assertFalse($featureFlags->isChannelActivated(ChannelId::SmsChannel));

        $featureFlags = new EnvChannelsFeatureFlags(true, false);
        $this->assertTrue($featureFlags->isChannelActivated(ChannelId::EmailChannel));
        $this->assertFalse($featureFlags->isChannelActivated(ChannelId::SmsChannel));

        $featureFlags = new EnvChannelsFeatureFlags(false, true);
        $this->assertFalse($featureFlags->isChannelActivated(ChannelId::EmailChannel));
        $this->assertTrue($featureFlags->isChannelActivated(ChannelId::SmsChannel));
    }
}
