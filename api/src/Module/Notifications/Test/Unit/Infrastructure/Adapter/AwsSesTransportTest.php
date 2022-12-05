<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Infrastructure\Adapter;

use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Infrastructure\Adapter\AwsSesTransport;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;

class AwsSesTransportTest extends TestCase
{
    /** @test */
    public function appropriateExceptionIsThrownWhenMailerComponentFails(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->method('send')->willThrowException(new TransportException(''));

        $transport = new AwsSesTransport($mailer);

        $this->expectException(TransportFailedException::class);
        $transport->send(
            new Recipient(Email::create('email@email.com'), Phone::create('516471016')),
            new Notification('Title', 'Content')
        );
    }
}
