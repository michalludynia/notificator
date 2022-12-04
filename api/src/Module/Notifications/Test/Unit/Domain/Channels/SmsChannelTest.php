<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Domain\Channels;

use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\Channels\SmsChannel;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Domain\ValueObject\TransportId;
use PHPUnit\Framework\TestCase;

class SmsChannelTest extends TestCase
{
    /** @test */
    public function channelSendNotificationWithFirstAvailableTransportOnly(): void
    {
        // given
        $firstSmsTransport = $this->createMock(Transport::class);
        $firstSmsTransport->method('isAvailable')->willReturn(true);
        $firstSmsTransport->method('getId')->willReturn(TransportId::fromString('1'));
        $secondSmsTransport = $this->createMock(Transport::class);
        $secondSmsTransport->method('isAvailable')->willReturn(true);

        // then
        $firstSmsTransport->expects($this->once())->method('send');
        $secondSmsTransport->expects($this->never())->method('send');

        // when
        $this->sendNotification([$firstSmsTransport, $secondSmsTransport]);
    }

    /** @test */
    public function channelSkipsNotAvailableTransports(): void
    {
        // given
        $firstSmsTransport = $this->createMock(Transport::class);
        $firstSmsTransport->method('isAvailable')->willReturn(false);
        $secondSmsTransport = $this->createMock(Transport::class);
        $secondSmsTransport->method('isAvailable')->willReturn(true);
        $secondSmsTransport->method('getId')->willReturn(TransportId::fromString('1'));

        // then
        $firstSmsTransport->expects($this->never())->method('send');
        $secondSmsTransport->expects($this->once())->method('send');

        // when
        $this->sendNotification([$firstSmsTransport, $secondSmsTransport]);
    }

    /** @param Transport[] $emailTransports*/
    private function sendNotification(array $emailTransports): void
    {
        $channel = new SmsChannel(
            $emailTransports,
            $this->createMock(ChannelsFeatureFlags::class),
        );

        $channel->sendNotification(
            new Recipient(
                Email::create('email@email.com'),
                Phone::create('phone')
            ),
            new Notification(
                'title',
                'content',
            ),
        );
    }
}
