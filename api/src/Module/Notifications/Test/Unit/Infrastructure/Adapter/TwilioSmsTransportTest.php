<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Infrastructure\Adapter;

use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Infrastructure\Adapter\TwilioSmsTransport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\NotifierInterface;

class TwilioSmsTransportTest extends TestCase
{
    /** @test */
    public function appropriateExceptionIsThrownWhenNotificatorComponentFails(): void
    {
        $notifier = $this->createMock(NotifierInterface::class);
        $notifier->method('send')->willThrowException(new \Exception(''));

        $transport = new TwilioSmsTransport($notifier);

        $this->expectException(TransportFailedException::class);
        $transport->send(
            new Recipient(Email::create('email@email.com'), Phone::create('516471016')),
            new Notification('Title', 'Content')
        );
    }
}
