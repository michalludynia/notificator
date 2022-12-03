<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class Phone
{
    private function __construct(
        private readonly string $value
    ) {
    }

    public static function create(string $phone): self
    {
        return new self($phone);
    }

    public function getValue(): string
    {
        return $this->value;
    }

}