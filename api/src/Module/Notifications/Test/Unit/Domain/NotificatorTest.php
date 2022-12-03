<?php

declare(strict_types=1);

namespace Notifications\Test\Unit;

use Notifications\Domain\NotificationChannels\EmailChannel;
use Notifications\Domain\NotificationChannels\NotificationChannel;
use Notifications\Domain\NotificationChannels\PhoneChannel;
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
    public function notificatorReturnsFailureResultWhenNoneOfProvidersIsAvailable(): void
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
        $this->assertEquals(FailureReason::NONE_OF_PROVIDERS_IS_AVAILABLE, $result->failureReason);
    }


    /** @param NotificationChannel[] $notificationChannels*/
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
