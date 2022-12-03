<?php

declare(strict_types=1);

namespace Notifications\Application\Port;

use Notifications\Application\DTO\MessageDTO;

interface MessagesPort
{
    public function getContentForLanguage(string $messageId, string $languageCode): MessageDTO;
}
