<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum FailureReason: string
{
    case ALL_AVAILABLE_PROVIDERS_FAILED = 'ALL_AVAILABLE_PROVIDERS_FAILED';
}
