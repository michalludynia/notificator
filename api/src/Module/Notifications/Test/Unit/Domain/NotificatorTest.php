<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Domain;

use Notifications\Domain\Channels\Channel;
use Notifications\Domain\Channels\ChannelsActivationFlags;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\Channels\PhoneChannel;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\Notificator;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\FailureReason;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Test\TestDouble\FakeChannelsActivationFlags;
use Notifications\Test\TestDouble\FakeEmailTransport;
use Notifications\Test\TestDouble\FakePhoneTransport;
use PHPUnit\Framework\TestCase;

class NotificatorTest extends TestCase
{
    /** @test */
    public function notificatorUseOnlyActivatedServices(): void
    {
        // given
        $activationFlags = new FakeChannelsActivationFlags([
                PhoneChannel::getId(),
            ]
        );

        $notificationChannels = [
            new EmailChannel(
                [new FakeEmailTransport(true)],
                $activationFlags,
            ),
            new PhoneChannel(
                [new FakePhoneTransport(true)],
                $activationFlags,
            ),
        ];

        // when
        $result = $this->sendNotification($notificationChannels);

        // then
        $this->assertEquals(PhoneChannel::getId(), $result->usedChannel);
        $this->assertTrue($result->hasSucceed);
    }

    /**
     * @test
     */
    public function notificatorReturnsFailureResultWhenNoneOfProvidersIsActivated(): void
    {
        // given
        $activationFlags = new FakeChannelsActivationFlags([]);

        $notificationChannels = [
            new EmailChannel(
                [new FakeEmailTransport(true)],
                $activationFlags,
            ),
            new PhoneChannel(
                [new FakePhoneTransport(true)],
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

        $firstPhoneTransport = $this->createMock(Transport::class);
        $firstPhoneTransport->method('isAvailable')->willReturn(true);

        $activationFlags = $this->createMock(ChannelsActivationFlags::class);
        $activationFlags->method('isChannelActivated')->willReturn(true);

        // then

        $firstEmailTransport->expects($this->once())->method('send');
        $secondEmailTransport->expects($this->once())->method('send');
        $firstPhoneTransport->expects($this->once())->method('send');

        $notificationChannels = [
            new EmailChannel(
                [
                    $firstEmailTransport,
                    $secondEmailTransport,
                ],
                $activationFlags,
            ),
            new PhoneChannel(
                [
                    $firstPhoneTransport,
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
