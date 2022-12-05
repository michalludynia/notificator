<?php

declare(strict_types=1);

namespace Messages\Test\Unit\Domain\ValueObject;

use Messages\Domain\ValueObject\MessageId;
use PHPUnit\Framework\TestCase;

class MessageIdTest extends TestCase
{
    /** @test */
    public function objectsWithTheSameValueAreEqual(): void
    {
        $firstMessageId = MessageId::fromString('SomeMessageId');
        $secondMessageId = MessageId::fromString('SomeMessageId');

        $this->assertTrue($firstMessageId->equals($secondMessageId));
    }

    /** @test */
    public function objectsWithDifferentValueAreEqual(): void
    {
        $firstMessageId = MessageId::fromString('SomeMessageId');
        $secondMessageId = MessageId::fromString('AnotherMessageId');

        $this->assertFalse($firstMessageId->equals($secondMessageId));
    }
}
