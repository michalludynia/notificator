<?php

declare(strict_types=1);

namespace Messages\Test\Unit\Domain;

use Messages\Domain\Message;
use Messages\Domain\ValueObject\LanguageCode;
use Messages\Domain\ValueObject\LocalisedContent;
use Messages\Domain\ValueObject\MessageId;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @test */
    public function getLocalisedContentTest(): void
    {
        $expectedEnContent = new LocalisedContent(
            LanguageCode::En,
            'Greeting 1',
            'Hello, how are you?'
        );

        $expectedPlContent = new LocalisedContent(
            LanguageCode::Pl,
            'Przywitanie 1',
            'Cześć, jak się masz?'
        );

        $message = new Message(
            MessageId::fromString('1'),
            [$expectedEnContent, $expectedPlContent]
        );

        $this->assertEquals(
            $expectedEnContent,
            $message->getLocalisedContent(LanguageCode::En)
        );

        $this->assertEquals(
            $expectedPlContent,
            $message->getLocalisedContent(LanguageCode::Pl)
        );
    }
}
