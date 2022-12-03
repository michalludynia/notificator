<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum FailureReason: string
{
    case NONE_OF_PROVIDERS_IS_AVAILABLE = 'NONE_OF_PROVIDERS_IS_AVAILABLE';
}
