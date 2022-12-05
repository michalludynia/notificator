<?php

declare(strict_types=1);

namespace ExampleClient\Infrastructure\Adapter;

use ExampleClient\Application\DTO\MessageDTO;
use ExampleClient\Application\Port\MessagesPort;
use Messages\Domain\Messages;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\MessageId;

class MessagesAdapter implements MessagesPort
{
    public function __construct(
        private readonly Messages $messages
    ) {
    }

    public function allMessagesIds(): array
    {
        $titles = [];

        foreach ($this->messages->getAll() as $message) {
            $titles[] = $message->id->value;
        }

        return $titles;
    }

    public function getLocalised(string $messageId, string $languageCode): MessageDTO
    {
        $message = $this->messages->find(MessageId::fromString($messageId));

        $content = $message->getContentInLanguage(LanguageCode::from($languageCode));

        return new MessageDTO(
            $message->id->value,
            $content->title,
            $content->content
        );
    }

    public function availableLanguagesCodes(): array
    {
        return array_map(static fn (LanguageCode $languageCode) => $languageCode->value, LanguageCode::cases());
    }
}
