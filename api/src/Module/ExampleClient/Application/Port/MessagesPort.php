<?php

declare(strict_types=1);

namespace ExampleClient\Application\Port;

use ExampleClient\Application\DTO\MessageDTO;

interface MessagesPort
{
    /** @return string[] */
    public function allMessagesIds(): array;

    public function getLocalised(string $messageId, string $languageCode): MessageDTO;

    /** @return string[] */
    public function availableLanguagesCodes(): array;
}
