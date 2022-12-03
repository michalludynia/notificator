<?php

declare(strict_types=1);

namespace Messages\Domain\ValueObject;

class MessageId
{
    private function __construct(
        public readonly string $value
    ) {
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function equals(MessageId $messageId): bool
    {
        return $this->value === $messageId->value;
    }
}
