<?php

declare(strict_types=1);

namespace Notifications\Presentation\Cli;

use Notifications\Application\Command\NotifyCustomers;
use Notifications\Application\DTO\CustomerDTO;
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
            new NotifyCustomers(
                '1',
                [
                    new CustomerDTO(
                        'ludynia.michal@gmail.com',
                        '0048516471016',
                        'pl'
                    ),
                    new CustomerDTO(
                        'dummy.fau@gmail.com',
                        '0048516471015',
                        'en'
                    ),
                ],
            )
        );

        return 0;
    }
}
