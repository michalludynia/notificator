<?php

declare(strict_types=1);

namespace Notifications\Application\Handler;

use Notifications\Application\Command\NotifyCustomers;
use Notifications\Application\Command\SendNotification;
use Notifications\Application\DTO\CustomerDTO;
use Notifications\Application\DTO\NotificationDTO;
use Notifications\Application\DTO\RecipientDTO;
use Notifications\Application\Port\MessagesPort;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class NotifyCustomersHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessagesPort $messagesPort,
        private readonly MessageBusInterface $commandBus
    ) {
    }

    public function __invoke(NotifyCustomers $command): void
    {
        /** @var CustomerDTO $customer */
        foreach ($command->customers as $customer) {

            $localisedMessageContent = $this->messagesPort->getLocalised(
                $command->messageId,
                $customer->preferredLanguage
            );

            $this->commandBus->dispatch(
                new SendNotification(
                    new RecipientDTO(
                        $customer->email,
                        $customer->phone,
                    ),
                    new NotificationDTO(
                        $localisedMessageContent->title,
                        $localisedMessageContent->content,
                    )
                )
            );
        }
    }
}
