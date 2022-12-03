<?php

declare(strict_types=1);

namespace Messages\Infrastructure;

use Messages\Domain\Message;
use Messages\Domain\Messages;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\LocalisedContent;
use Messages\Domain\ValueObject\MessageId;

class InMemoryMessages implements Messages
{
    public function find(MessageId $messageId): Message
    {
        $found = array_values(
            array_filter(
                $this->storage(),
                static fn (Message $message) => $message->id->equals($messageId)
            )
        );

        if (empty($found)) {
            throw new \RuntimeException('Message with given id not found');
        }

        return $found[0];
    }

    /** @return Message[] */
    private function storage(): array
    {
        return [
            new Message(
                MessageId::fromString('1'),
                [
                    new LocalisedContent(
                        LanguageCode::En,
                        'Greeting 1',
                        'Hello, how are you?'
                    ),
                    new LocalisedContent(
                        LanguageCode::Pl,
                        'Przywitanie 1',
                        'Cześć, jak się masz?'
                    ),
                ]
            ),
            new Message(
                MessageId::fromString('2'),
                [
                    new LocalisedContent(
                        LanguageCode::En,
                        'Greeting 2',
                        'Hello, have a nice day!'
                    ),
                    new LocalisedContent(
                        LanguageCode::Pl,
                        'Przywitanie 2',
                        'Cześć, miłego dnia!'
                    ),
                ]
            ),
        ];
    }
}
