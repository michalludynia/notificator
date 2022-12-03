<?php

declare(strict_types=1);

namespace Notifications\Domain\ValueObject;

enum TransportId: string
{
    case EMAIL_TRANSPORT_AWS_SES = 'email_transport_aws_ses';
    case PHONE_TRANSPORT_TWILIO = 'phone_transport_twilio';
}
