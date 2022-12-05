<?php

declare(strict_types=1);

namespace Messages\Domain;

use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\LocalisedContent;
use Messages\Domain\ValueObject\LocalisedContentCollection;
use Messages\Domain\ValueObject\MessageId;

class Message
{
    public function __construct(
        public readonly MessageId $id,
        public readonly LocalisedContentCollection $localisedContents
    ) {
    }

    public function getContentInLanguage(LanguageCode $languageCode): LocalisedContent
    {
        return $this->localisedContents->getContentInLanguage($languageCode);
    }
}
