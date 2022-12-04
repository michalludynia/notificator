<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Domain;

use Notifications\Domain\Channels\Channel;
use Notifications\Domain\Channels\ChannelsFeatureFlags;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\Channels\SmsChannel;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\Notificator;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\FailureReason;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Test\TestDouble\FakeChannelsFeatureFlags;
use Notifications\Test\TestDouble\FakeEmailTransport;
use Notifications\Test\TestDouble\FakeSmsTransport;
use PHPUnit\Framework\TestCase;

class NotificatorTest extends TestCase
{
    /** @test */
    public function notificatorUseOnlyActivatedServices(): void
    {
        // given
        $activationFlags = new FakeChannelsFeatureFlags([
                SmsChannel::getId(),
            ]
        );

        $notificationChannels = [
            new EmailChannel(
                [new FakeEmailTransport(true)],
                $activationFlags,
            ),
            new SmsChannel(
                [new FakeSmsTransport(true)],
                $activationFlags,
            ),
        ];

        // when
        $result = $this->sendNotification($notificationChannels);

        // then
        $this->assertEquals(SmsChannel::getId(), $result->usedChannel);
        $this->assertTrue($result->hasSucceed);
    }

    /**
     * @test
     */
    public function notificatorReturnsFailureResultWhenNoneOfProvidersIsActivated(): void
    {
        // given
        $activationFlags = new FakeChannelsFeatureFlags([]);

        $notificationChannels = [
            new EmailChannel(
                [new FakeEmailTransport(true)],
                $activationFlags,
            ),
            new SmsChannel(
                [new FakeSmsTransport(true)],
                $activationFlags,
            ),
        ];

        // when
        $result = $this->sendNotification($notificationChannels);

        // then
        $this->assertFalse($result->hasSucceed);
        $this->assertEquals(FailureReason::ALL_AVAILABLE_PROVIDERS_FAILED, $result->failureReason);
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
    private function sendNotification(array $notificationChannels): NotificationResult
    {
        return (new Notificator($notificationChannels))->notify(
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
