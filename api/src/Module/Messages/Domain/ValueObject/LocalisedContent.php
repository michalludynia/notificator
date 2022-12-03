<?php

declare(strict_types=1);

namespace Messages\Domain\ValueObject;

class LocalisedContent
{
    public function __construct(
        public readonly LanguageCode $language,
        public readonly string $content,
    ) {
    }
}
