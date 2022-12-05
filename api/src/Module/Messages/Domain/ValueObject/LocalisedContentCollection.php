<?php

declare(strict_types=1);

namespace Messages\Domain\ValueObject;

class LocalisedContentCollection
{
    /** @param LocalisedContent[] $contents */
    public function __construct(
        private readonly array $contents
    ) {
    }

    public function getContentInLanguage(LanguageCode $languageCode): LocalisedContent
    {
        $matchingContents = array_values(
            array_filter(
                $this->contents,
                static fn (LocalisedContent $content) => $content->languageCode === $languageCode
            ));

        return $matchingContents[0];
    }
}
