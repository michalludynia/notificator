<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Infrastructure\Adapter;

use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Infrastructure\Adapter\LoggableTransportDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class LoggableTransportDecoratorTest extends TestCase
{
    /** @test */
    public function infoIsCalledOnLoggerWhenTransportSucceeds(): void
    {
        $transport = $this->createMock(Transport::class);
        $logger = $this->createMock(LoggerInterface::class);

        $logger->expects($this->once())->method('info');

        $loggableDecorator = new LoggableTransportDecorator(
            $transport,
            $logger
        );

        $loggableDecorator->send(
            new Recipient(Email::create('email@emai.com'), Phone::create('phone')),
            new Notification('Title', 'Message')
        );
    }

    /** @test */
    public function noticeIsCalledOnLoggerWhenTransportFails(): void
    {
        $transport = $this->createMock(Transport::class);
        $logger = $this->createMock(LoggerInterface::class);
        $transport->method('send')->willThrowException(new TransportFailedException());

        $logger->expects($this->once())->method('notice');

        $loggableDecorator = new LoggableTransportDecorator(
            $transport,
            $logger
        );

        $this->expectException(TransportFailedException::class);

        $loggableDecorator->send(
            new Recipient(Email::create('email@emai.com'), Phone::create('phone')),
            new Notification('Title', 'Message')
        );
    }
}
