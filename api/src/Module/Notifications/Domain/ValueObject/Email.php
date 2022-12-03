<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

class Email
{
    private function __construct(
        private readonly string $value
    ) {
    }

    public static function create(string $email): self
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Email not valid');
        }

        return new self($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
