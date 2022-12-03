<?php

declare(strict_types=1);

namespace Notifications\Infrastructure\Factory;

use Ackintosh\Ganesha;
use Ackintosh\Ganesha\Builder;
use Ackintosh\Ganesha\Storage\Adapter\Redis;
use Predis\Client;
use Psr\Log\LoggerInterface;

class GaneshaFactory
{
    private const failureRateThreshold = 50;
    private const intervalToHalfOpen = 10;
    private const minimumRequests = 3;
    private const timeWindow = 120;

    public function __construct(
        private readonly Client $redis,
        private readonly LoggerInterface $logger
    ) {
    }

    public function build(): Ganesha
    {
        $ganesha = Builder::withRateStrategy()
            ->adapter(new Redis($this->redis))
            ->failureRateThreshold(self::failureRateThreshold)
            ->intervalToHalfOpen(self::intervalToHalfOpen)
            ->minimumRequests(self::minimumRequests)
            ->timeWindow(self::timeWindow)
            ->build();

        $logger = $this->logger;

        $ganesha->subscribe(function ($event, $service, $message) use ($logger) {
            switch ($event) {
                case Ganesha::EVENT_TRIPPED:
                    $logger->alert(
                        "Ganesha has tripped! It seems that a failure has occurred in {$service}{$message}."
                    );
                    break;
                case Ganesha::EVENT_CALMED_DOWN:
                    $logger->alert(
                        "Ganesha circuit closed. The service {$service} seems to available again {$message}."
                    );
                    break;
                default:
                    break;
            }
        });

        return $ganesha;
    }
}
