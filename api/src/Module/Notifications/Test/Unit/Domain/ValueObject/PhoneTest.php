<?php

declare(strict_types=1);

namespace Notifications\Test\ValueObject;

use Notifications\Domain\ValueObject\Email;
use Notifications\Domain\ValueObject\Phone;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
{
    /** @test */
    public function createFromValidPhone(): void
    {
        $validPhone = '00123568043';

        $vo = Phone::create($validPhone);
        $this->assertEquals($validPhone, $vo->getValue());
    }
}
