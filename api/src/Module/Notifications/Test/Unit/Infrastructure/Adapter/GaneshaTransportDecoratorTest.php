<?php

declare(strict_types=1);

namespace Notifications\Test\Unit\Infrastructure\Adapter;

use Ackintosh\Ganesha;
use Notifications\Domain\Channels\Transports\Transport;
use Notifications\Domain\Exception\TransportFailedException;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Notifications\Infrastructure\Adapter\GaneshaTransportDecorator;
use PHPUnit\Framework\TestCase;

class GaneshaTransportDecoratorTest extends TestCase
{
    public function successIsCalledOnGaneshaWhenTransportSucceeds(): void
    {
        $transport = $this->createMock(Transport::class);
        $ganesha = $this->createMock(Ganesha::class);

        $ganesha->expects($this->once())->method('success');

        $ganeshaDecorator = new GaneshaTransportDecorator(
            $transport,
            $ganesha
        );

        $ganeshaDecorator->send(
            new Recipient(Email::create('email@emai.com'), Phone::create('phone')),
            new Notification('Title', 'Message')
        );
    }

    /**
     * @test
     */
    public function failureIsCalledOnGaneshaWhenTransportFails(): void
    {
        $transport = $this->createMock(Transport::class);
        $ganesha = $this->createMock(Ganesha::class);

        $transport->method('send')->willThrowException(new TransportFailedException());

        $ganesha->expects($this->once())->method('failure');

        $ganeshaDecorator = new GaneshaTransportDecorator(
            $transport,
            $ganesha
        );

        $this->expectException(TransportFailedException::class);
        $ganeshaDecorator->send(
            new Recipient(Email::create('email@emai.com'), Phone::create('phone')),
            new Notification('Title', 'Message')
        );
    }
}
