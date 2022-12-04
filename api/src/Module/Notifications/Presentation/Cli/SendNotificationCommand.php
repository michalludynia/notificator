<?php

declare(strict_types=1);

namespace Notifications\Presentation\Cli;

use Messages\Domain\ValueObject\LanguageCode;
use Notifications\Application\Command\NotifyCustomers;
use Notifications\Application\DTO\CustomerDTO;
use Notifications\Application\Port\MessagesPort;
use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Phone;
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
            ->setDescription('Send notification')
            ->setName('notifications:send');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $messageIdQuestion = new ChoiceQuestion(
            'Provide message id (1 or 2): ',
            $this->messagesPort->allMessagesIds()
        );

        $customerEmailQuestion = new Question('Provide customer email: ');
        $customerEmailQuestion->setValidator(static function (mixed $answer) {
            if (!\is_string($answer)) {
                throw new \RuntimeException('Invalid email');
            }
            Email::create($answer);

            return $answer;
        });

        $customerPhoneQuestion = new Question('Provide customer phone number: ');
        $customerPhoneQuestion->setValidator(static function (mixed $answer) {
            if (!\is_string($answer)) {
                throw new \RuntimeException('Invalid phone');
            }
            Phone::create($answer);

            return $answer;
        });

        $customerLanguageQuestion = new ChoiceQuestion(
            'Provide customer preferred language (en or pl): ',
            array_map(static fn (LanguageCode $languageCode) => $languageCode->value, LanguageCode::cases())
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
