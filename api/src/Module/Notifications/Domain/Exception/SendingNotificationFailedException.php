<?php

declare(strict_types=1);

namespace Notifications\Domain\Exception;

class SendingNotificationFailedException extends AbstractDomainException
{
    public function __construct(string $failureReason)
    {
        parent::__construct(
            sprintf(
                '[SENDING_NOTIFICATION_FAILED] Reason of failure: %s', $failureReason
            )
        );
    }
}
