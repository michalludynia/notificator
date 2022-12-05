<?php

declare(strict_types=1);

namespace ExampleClient\Presentation\Cli;

use ExampleClient\Application\Command\NotifyCustomers;
use ExampleClient\Application\DTO\CustomerDTO;
use ExampleClient\Application\Port\MessagesPort;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

class SendNotificationCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessagesPort $messagesPort
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Command sends a notification to single provided customer')
            ->setName('example-client:notify-customer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $messageIdQuestion = new ChoiceQuestion(
            'Choose one available messages: ',
            $this->messagesPort->allMessagesIds()
        );

        $customerEmailQuestion = new Question('Provide customer email: ');
        $customerEmailQuestion->setValidator(static function (mixed $answer) {
            if (!\is_string($answer)) {
                throw new \RuntimeException('Invalid email');
            }

            if (false === filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Invalid email');
            }

            return $answer;
        });

        $customerPhoneQuestion = new Question('Provide customer phone number: ');
        $customerPhoneQuestion->setValidator(static function (mixed $answer) {
            if (!\is_string($answer)) {
                throw new \RuntimeException('Invalid phone');
            }

            return $answer;
        });

        $customerLanguageQuestion = new ChoiceQuestion(
            'Choose customer preferred language: ',
            $this->messagesPort->availableLanguagesCodes()
        );

        $messageId = (string) $helper->ask($input, $output, $messageIdQuestion);
        $email = (string) $helper->ask($input, $output, $customerEmailQuestion);
        $phone = (string) $helper->ask($input, $output, $customerPhoneQuestion);
        $language = (string) $helper->ask($input, $output, $customerLanguageQuestion);

        $continueQuestion = new ConfirmationQuestion('Are you sure you want to notify provided users? (yes/no) <info>[no]</info> ', false);

        if (!$helper->ask($input, $output, $continueQuestion)) {
            return Command::SUCCESS;
        }

        $this->commandBus->dispatch(
            new NotifyCustomers(
                $messageId,
                [
                    new CustomerDTO(
                        $email,
                        $phone,
                        $language
                    ),
                ],
            )
        );

        return 0;
    }
}
