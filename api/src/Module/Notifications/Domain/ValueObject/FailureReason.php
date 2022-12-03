<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum FailureReason: string
{
    case ALL_PROVIDERS_ARE_DOWN = 'ALL_PROVIDERS_ARE_DOWN';
}
