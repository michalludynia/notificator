<?php

declare(strict_types=1);

namespace Notifications\Presentation\Cli;

use Notifications\Application\Command\SendNotification;
use Notifications\Application\DTO\ReceiverDTO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNotificationCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send notification')
            ->setName('notifications:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(
            new SendNotification(
                '1',
                new ReceiverDTO(
                    'email@email.com',
                    '0048516471016',
                ),
                'pl'
            )
        );

        return 0;
    }
}
