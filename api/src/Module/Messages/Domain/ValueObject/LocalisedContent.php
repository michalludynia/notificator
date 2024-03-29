<?php

declare(strict_types=1);

namespace Messages\Domain\ValueObject;

class LocalisedContent
{
    public function __construct(
        public readonly LanguageCode $languageCode,
        public readonly string $title,
        public readonly string $content,
    ) {
    }
}
