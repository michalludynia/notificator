<?php

declare(strict_types=1);

namespace Notifications\Test\ValueObject;

use Notifications\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /** @test */
    public function createFromValidEmail(): void
    {
        $validEmail = 'email.email@email.com';

        $vo = Email::create($validEmail);
        $this->assertEquals($validEmail, $vo->getValue());
    }

    /**
     * @test
     *
     * @dataProvider invalidEmailDataProvider()
     */
    public function throwOnInvalidEmail(string $invalidEmail): void
    {
        $this->expectException(\RuntimeException::class);
        Email::create($invalidEmail);
    }

    /** @return string[] */
    private function invalidEmailDataProvider(): array
    {
        return [
            [
                'email',
                'email.email',
                '',
                '123',
            ],
        ];
    }
}
