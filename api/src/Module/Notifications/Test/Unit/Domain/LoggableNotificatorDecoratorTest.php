<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Domain;

use Notifications\Domain\LoggableNotificator;
use Notifications\Domain\NotificationsLogs;
use Notifications\Domain\Notificator;
use Notifications\Domain\ValueObject\ChannelId;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\NotificationResult;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Receiver;
use Notifications\Domain\ValueObject\TransportId;
use PHPUnit\Framework\TestCase;

class LoggableNotificatorDecoratorTest extends TestCase
{
    /** @test */
    public function operationIsLoggedAfterNotifing(): void
    {
        $notificator = $this->createMock(Notificator::class);
        $notificator->method('notify')
            ->willReturn(NotificationResult::success(
                ChannelId::fromString('1'),
                TransportId::fromString('2')
            )
            );

        $notificationLogs = $this->createMock(NotificationsLogs::class);
        $notificationLogs->expects($this->once())->method('log');

        $loggableNotificator = new LoggableNotificator(
            $notificator, $notificationLogs
        );

        $loggableNotificator->notify(
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
