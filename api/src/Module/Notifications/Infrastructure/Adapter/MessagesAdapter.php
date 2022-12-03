<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Adapter;

use Messages\Domain\Messages;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\MessageId;
use Notifications\Application\DTO\MessageContentDTO;
use Notifications\Application\Port\MessagesPort;

class MessagesAdapter implements MessagesPort
{
    public function __construct(
        private readonly Messages $messages
    ) {
    }

    public function getContentForLanguage(string $messageId, string $languageCode): MessageContentDTO
    {
        $localisedMessageContent = $this->messages
            ->find(MessageId::fromString($messageId))
            ->getLocalisedContent(LanguageCode::from($languageCode));

        return new MessageContentDTO(
            $localisedMessageContent->content
        );
    }
}
