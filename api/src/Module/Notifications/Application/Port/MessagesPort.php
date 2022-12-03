<?php

declare(strict_types=1);

namespace Notifications\Application\Port;

use Notifications\Application\DTO\MessageContentDTO;

interface MessagesPort
{
    public function getContentForLanguage(string $messageId, string $languageCode): MessageContentDTO;
}
