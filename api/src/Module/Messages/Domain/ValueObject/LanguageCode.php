<?php

declare(strict_types=1);

namespace Messages\Domain\ValueObject;

enum LanguageCode: string
{
    case En = 'en';
    case Pl = 'pl';
}
