Feature: Behaviour of notifications

  Scenario: In order to notify the customers at least one channel have to be active.
    Given EmailChannel is inactive
    And SmsChannel is active
    When Message with id 1 is being send to 2 customers
    Then Notification has been sent successfully

  Scenario: Notification sending fails when all channels has been deactivated.
    Given EmailChannel is inactive
    And SmsChannel is inactive
    When Message with id 1 is being send to 2 customers
    Then Notification sending has failed

  Scenario: Notifications are send with backup channels when primary channel fails
    Given EmailChannel is active
    And SmsChannel is active
    When Message with id GreetingMessage is being send to 2 customers
    And EmailChannel is inactive
    And Message with id GoodbyeMessage is being send to 3 customers
    And EmailChannel is active
    And Message with id GreetingMessage is being send to 3 customers
    Then EmailChannel should be used 5 times
    And SmsChannel should be used 3 times