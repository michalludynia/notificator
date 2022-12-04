<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Domain;

use Notifications\Domain\Channels\Channel;
use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\Channels\SmsChannel;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\SendingNotificationFailedException;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\Notificator;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use PHPUnit\Framework\TestCase;

class NotificatorTest extends TestCase
{
    /** @test */
    public function notificatorUseOnlyActivatedChannels(): void
    {
        // given
        $emailChannel = $this->createMock(EmailChannel::class);
        $emailChannel->method('isActivated')->willReturn(false);

        $smsChannel = $this->createMock(SmsChannel::class);
        $smsChannel->method('isActivated')->willReturn(true);

        // then
        $emailChannel->expects($this->never())->method('sendNotification');
        $smsChannel->expects($this->once())->method('sendNotification');

        // when
        $this->sendNotification([$emailChannel, $smsChannel]);
    }

    /**
     * @test
     */
    public function notificatorReturnsFailureResultWhenAllChannelsAreDeActivated(): void
    {
        // given
        $emailChannel = $this->createMock(EmailChannel::class);
        $emailChannel->method('isActivated')->willReturn(false);

        $smsChannel = $this->createMock(SmsChannel::class);
        $smsChannel->method('isActivated')->willReturn(false);

        // then
        $this->expectException(SendingNotificationFailedException::class);

        // when
        $this->sendNotification([$emailChannel, $smsChannel]);
    }

    /**
     * @test
     */
    public function notificatorProceedsToTheNextChannelWhenAllTransportsFromPreviousChannelFailed(): void
    {
        // given
        $firstEmailTransport = $this->createMock(Transport::class);
        $firstEmailTransport->method('isAvailable')->willReturn(true);
        $firstEmailTransport->method('send')->willThrowException(new TransportFailedException());

        $secondEmailTransport = $this->createMock(Transport::class);
        $secondEmailTransport->method('isAvailable')->willReturn(true);
        $secondEmailTransport->method('send')->willThrowException(new TransportFailedException());

        $firstSmsTransport = $this->createMock(Transport::class);
        $firstSmsTransport->method('isAvailable')->willReturn(true);

        $activationFlags = $this->createMock(ChannelsFeatureFlags::class);
        $activationFlags->method('isChannelActivated')->willReturn(true);

        // then

        $firstEmailTransport->expects($this->once())->method('send');
        $secondEmailTransport->expects($this->once())->method('send');
        $firstSmsTransport->expects($this->once())->method('send');

        $notificationChannels = [
            new EmailChannel(
                [
                    $firstEmailTransport,
                    $secondEmailTransport,
                ],
                $activationFlags,
            ),
            new SmsChannel(
                [
                    $firstSmsTransport,
                ],
                $activationFlags,
            ),
        ];

        // when
        $this->sendNotification($notificationChannels);
    }

    /** @param Channel[] $notificationChannels*/
    private function sendNotification(array $notificationChannels): void
    {
        (new Notificator($notificationChannels))->notify(
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
