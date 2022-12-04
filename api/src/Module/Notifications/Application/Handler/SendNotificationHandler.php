<?php

declare(strict_types=1);

namespace Notifications\Application\Handler;

use Notifications\Application\Command\SendNotification;
use Notifications\Domain\NotificatorInterface;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Recipient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly NotificatorInterface $notificator,
    ) {
    }

    public function __invoke(SendNotification $command): void
    {
        $this->notificator->notify(
            new Recipient(
                Email::create($command->recipient->email),
                Phone::create($command->recipient->phone),
            ),
            new Notification(
                $command->notification->title,
                $command->notification->content
            )
        );
    }
}
