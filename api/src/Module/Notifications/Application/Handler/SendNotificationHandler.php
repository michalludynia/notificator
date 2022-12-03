<?php

declare(strict_types=1);

namespace Notifications\Application\Handler;

use Application\Command\SendNotification;
use Notifications\Application\Port\MessagesPort;
use Notifications\Domain\NotificatorInterface;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\MessageId;
use Notifications\Domain\ValueObject\Notification;
use Notifications\Domain\ValueObject\Phone;
use Notifications\Domain\ValueObject\Receiver;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly NotificatorInterface $notificator,
        private readonly MessagesPort $messagesPort
    ) {
    }

    public function __invoke(SendNotification $command): void
    {
        $localisedMessageContent = $this->messagesPort->getContentForLanguage(
            $command->messageId,
            $command->preferredLanguage
        );

        $this->notificator->notify(
            new Receiver(
                Email::create($command->receiverDTO->email),
                Phone::create($command->receiverDTO->phone),
            ),
            new Notification(
                MessageId::fromString($command->messageId),
                $localisedMessageContent->title,
                $localisedMessageContent->content
            ),
        );
    }
}
