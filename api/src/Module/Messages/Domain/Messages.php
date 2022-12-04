<?php

declare(strict_types=1);

namespace Messages\Domain;

use Messages\Domain\ValueObject\MessageId;

interface Messages
{
    public function find(MessageId $messageId): Message;

    /** @return Message[] */
    public function getAll(): array;
}
