<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Messages\Domain\Messages;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\MessageId;
use Notifications\Application\DTO\MessageDTO;
use Notifications\Application\Port\MessagesPort;

class MessagesAdapter implements MessagesPort
{
    public function __construct(
        private readonly Messages $messages
    ) {
    }

    public function getContentForLanguage(string $messageId, string $languageCode): MessageDTO
    {
        $message = $this->messages->find(MessageId::fromString($messageId));

        $content = $message->getLocalisedContent(LanguageCode::from($languageCode));

        return new MessageDTO(
            $message->id->value,
            $content->title,
            $content->content
        );
    }
}
