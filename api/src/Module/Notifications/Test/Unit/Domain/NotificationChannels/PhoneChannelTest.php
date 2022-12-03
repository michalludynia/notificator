<?php

declare(strict_types=1);

namespace Notifications\Test\Unit;

use Notifications\Domain\NotificationChannels\EmailChannel;
use Notifications\Domain\NotificationChannels\PhoneChannel;
use Notifications\Domain\NotificationChannels\Transports\Transport;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;
use Notifications\Test\TestDouble\FakeChannelsActivationFlags;
use PHPUnit\Framework\TestCase;

class PhoneChannelTest extends TestCase
{
    /** @test */
    public function channelSendNotificationWithFirstAvailableTransportOnly(): void
    {
        // given
        $firstEmailTransport = $this->createMock(Transport::class);
        $firstEmailTransport->method('isAvailable')->willReturn(true);
        $firstEmailTransport->method('getId')->willReturn(TransportId::PHONE_TRANSPORT_TWILIO);
        $secondEmailTransport = $this->createMock(Transport::class);
        $secondEmailTransport->method('isAvailable')->willReturn(true);

        // then
        $firstEmailTransport->expects($this->once())->method('send');
        $secondEmailTransport->expects($this->never())->method('send');

        // when
        $this->sendNotification([$firstEmailTransport, $secondEmailTransport]);
    }

    /** @test */
    public function channelSkipsNotAvailableTransports(): void
    {
        // given
        $firstEmailTransport = $this->createMock(Transport::class);
        $firstEmailTransport->method('isAvailable')->willReturn(false);
        $secondEmailTransport = $this->createMock(Transport::class);
        $secondEmailTransport->method('isAvailable')->willReturn(true);
        $secondEmailTransport->method('getId')->willReturn(TransportId::PHONE_TRANSPORT_TWILIO);

        // then
        $firstEmailTransport->expects($this->never())->method('send');
        $secondEmailTransport->expects($this->once())->method('send');

        // when
        $this->sendNotification([$firstEmailTransport, $secondEmailTransport]);
    }

    /** @param Transport[] $emailTransports*/
    private function sendNotification(array $emailTransports): NotificationResult
    {
        $channel = new PhoneChannel(
            $emailTransports,
            new FakeChannelsActivationFlags(),
        );

        return $channel->sendNotification(
            new Receiver(
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
