<?php

declare(strict_types=1);

namespace Notifications\Application\Port;

use Notifications\Application\DTO\MessageDTO;

interface MessagesPort
{
    /** @return string[] */
    public function allMessagesIds(): array;

    public function getLocalised(string $messageId, string $languageCode): MessageDTO;
}
