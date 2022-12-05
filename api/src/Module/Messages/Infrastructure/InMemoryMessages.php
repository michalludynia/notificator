<?php

declare(strict_types=1);

namespace Messages\Infrastructure;

use Messages\Domain\Message;
use Messages\Domain\Messages;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\LocalisedContent;
use Messages\Domain\ValueObject\LocalisedContentCollection;
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

    public function getAll(): array
    {
        return $this->storage();
    }

    /** @return Message[] */
    private function storage(): array
    {
        return [
            new Message(
                MessageId::fromString('GreetingMessage'),
                new LocalisedContentCollection([
                        new LocalisedContent(
                            LanguageCode::En,
                            'Greeting message',
                            'Hello, how are you?'
                        ),
                        new LocalisedContent(
                            LanguageCode::Pl,
                            'Wiadomość powitalna',
                            'Cześć, jak się masz?'
                        ),
                    ]
                ),
            ),
            new Message(
                MessageId::fromString('GoodbyeMessage'),
                new LocalisedContentCollection([
                        new LocalisedContent(
                            LanguageCode::En,
                            'Goodbye message',
                            'See you soon.'
                        ),
                        new LocalisedContent(
                            LanguageCode::Pl,
                            'Wiadomość pożegnalna',
                            'Do zobaczenia wkrótce.'
                        ),
                    ]
                ),
            ),
        ];
    }
}
