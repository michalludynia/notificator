<?php

declare(strict_types=1);

namespace Notifications\Test\Business;

use Behat\Behat\Context\Context;
use Notifications\Application\Command\NotifyCustomers;
use Notifications\Application\DTO\CustomerDTO;
use Notifications\Domain\Channels\EmailChannel;
use Notifications\Domain\Channels\SmsChannel;
use Notifications\Test\Business\TestDouble\Repository\FeaturesFlagsFakeStorage;
use Notifications\Test\Business\TestDouble\Spy\ChannelUsageSpy;
use PHPUnit\Framework\Assert;
use Symfony\Component\Messenger\MessageBusInterface;

class FeatureContext implements Context
{
    private bool $notificationsSentSuccessfully = false;

    public function __construct(
        private readonly FeaturesFlagsFakeStorage $featuresFlagsFakeStorage,
        private readonly MessageBusInterface $commandBus,
        private readonly ChannelUsageSpy $channelUsageSpy
    ) {
    }

    /**
     * @Given /^(EmailChannel|SmsChannel) is active$/
     */
    public function channelIsInActive(string $channelId): void
    {
        if ('EmailChannel' === $channelId) {
            $this->featuresFlagsFakeStorage->markAsActive(EmailChannel::getId());

            return;
        }

        if ('SmsChannel' === $channelId) {
            $this->featuresFlagsFakeStorage->markAsActive(SmsChannel::getId());

            return;
        }

        throw new \RuntimeException('Provided channel not supported');
    }

    /**
     * @Given /^(EmailChannel|SmsChannel) is inactive$/
     */
    public function channelIsActive(string $channelId): void
    {
        if ('EmailChannel' === $channelId) {
            $this->featuresFlagsFakeStorage->markAsInActive(EmailChannel::getId());

            return;
        }

        if ('SmsChannel' === $channelId) {
            $this->featuresFlagsFakeStorage->markAsInActive(SmsChannel::getId());

            return;
        }

        throw new \RuntimeException('Provided channel not supported');
    }

    /**
     * @When /^Message with id (.*) is being send to (\d+) customers$/
     */
    public function messageWithIdShouldBeSendToCustomers(string $messageId, int $customersNumber): void
    {
        $customers = [];

        for ($i = 0; $i < $customersNumber; ++$i) {
            $customers[] = new CustomerDTO(
                $i.'@email.com',
                $i.'23123123',
                'pl'
            );
        }

        try {
            $this->commandBus->dispatch(
                new NotifyCustomers(
                    $messageId,
                    $customers
                )
            );
            $this->notificationsSentSuccessfully = true;
        } catch (\Exception $e) {
            $this->notificationsSentSuccessfully = false;
        }
    }

    /**
     * @Then /^Notification has been sent successfully$/
     */
    public function notificationHasBeenSentSuccessfully(): void
    {
        Assert::assertTrue($this->notificationsSentSuccessfully);
    }

    /**
     * @Then /^Notification sending has failed$/
     */
    public function notificationSendingHasFailed(): void
    {
        Assert::assertFalse($this->notificationsSentSuccessfully);
    }

    /**
     * @Then /^(EmailChannel|SmsChannel) should be used (\d+) times$/
     */
    public function channelShouldBeUsedTimes(string $channelId, int $times): void
    {
        if ('EmailChannel' === $channelId) {
            Assert::assertEquals($times, $this->channelUsageSpy->getEmailChannelUsage());

            return;
        }

        if ('SmsChannel' === $channelId) {
            Assert::assertEquals($times, $this->channelUsageSpy->getSmsChannelUsage());

            return;
        }

        throw new \RuntimeException('Provided channel not supported');
    }
}
