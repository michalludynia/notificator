<?php

declare(strict_types=1);

namespace Messages\Domain;

use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\LocalisedContent;
use Messages\Domain\ValueObject\MessageId;

class Message
{
    public function __construct(
        public readonly MessageId $id,
        /** @var LocalisedContent[] $localisedContents */
        public readonly array $localisedContents
    ) {
    }

    public function getLocalisedContent(LanguageCode $contentLanguage): LocalisedContent
    {
        $foundMessages = array_values(
            array_filter(
                $this->localisedContents,
                static fn (LocalisedContent $content) => $content->language === $contentLanguage
            ));

        return $foundMessages[0];
    }
}
