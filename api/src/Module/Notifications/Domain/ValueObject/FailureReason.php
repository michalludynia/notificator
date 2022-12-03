<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum FailureReason
{
    case ALL_PROVIDERS_ARE_DOWN;
}