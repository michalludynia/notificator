<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class ChannelId
{
    private function __construct(
        private readonly string $value
    ) {
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
