
# Notification service - recruitment task

Notification service with multiply channels and transports support, built on top of PHP symfony framework.
## Prerequisites
To get a local copy of the development environment up and running you need to fulfill prerequisites and follow installation steps.
If you don't have a Docker installed on your machine, please install it. The Docker Engine and Docker compose let you build this project easily.

To check if it's installed on your machine, you can use the following commands:

```bash
youruser@machinename:$ docker -v
Docker version 20.10.21, build baeda1f 
```

```bash
youruser@machinename:$ docker-compose -v
docker-compose version 2.13.0 
```

## How to install?

#### 1. Clone API repository
```bash
youruser@machine:$ git clone --branch main git@github.com:michalludynia/notificator.git notificator
```

#### 2. Change current directory
```bash
youruser@machine:$ cd notificator
```

#### 3. Fill environment file with required secrets.
By default application comes with stubs that will let you tu use it without providing any keys.

If you would like to use the application with the real notifications providers like AWS SES or Twilio, please fill the required secrets in the .env file and uncomment *#MAILER_DSN* AND *#TWILIO_DSN* variables to make it work.



#### 4. Build and run the project with command:
```bash
youruser@machine:/notificator $ make up
```





## Usage/Examples

#### 1. Send example notifications to provided customer
If you would like to test notification sending go into shell terminal of php container by running the command:
```bash
youruser@machine:/notificator $ make sh
```
Then, to start the process, use command:
```bash
youruser@machine:/notificator $ bin/console notification:send-to-customer
```
The terminal will ask you to provide basic customer information and choose the message you would like to send. \
The application comes with two ready to use messages called: *GreetingMessage* and *GoodbyeMessage*. Both, are available in two language versions: English and Polish.

After executing the command you should see some similar message to the one below, saying the notification has been sent successfully:

```bash
17:39:05 INFO [notifications_logs] [NOTIFICATOR_SUCCEEDED] 
Receiver: "email@email.com/123123123" MessageTitle: "Greeting message" Transport: "email_transport_aws_ses"
```
The log visible in the console, will be also available through container, located in the file */var/log/notification.log*

#### 2. Enable/disable specific notifications channels
You can choose which notification channels should be enabled at the moment. To do so, you have to edit the environment variables.

Below you can see all that channels are enabled. If you would like to turn off the specific one, just change the boolean value from true to false.
```
#Channels feature flags
USE_EMAIL_CHANNEL=true
USE_SMS_CHANNEL=true
```

## Running Tests

To run tests, run the following command:

```bash
youruser@machine:/notificator $ make test-all
```

The command will run tests from phpunit and behat frameworks. However, if you would like to \
run only one specific framework you can always execute of the command:

```bash
youruser@machine:/notificator $ make test-unit
```

```bash
youruser@machine:/notificator $ make test-business
```
## Architecture & trade-offs
#### Layers
The project is build on top of symfony skeleton. The code is divided into four layers:
* **Application** - communication layer, handling domain orchestration and queues communication.
* **Domain** - the core, model supporting the business domain.
* **Infrastructure** - implementations, vendors, technical dependencies.
* **Presentation** - inputs and outputs

To get deeper view into used architecture:
https://herbertograca.com/2017/11/16/explicit-architecture-01-ddd-hexagonal-onion-clean-cqrs-how-i-put-it-all-together/#application-core-organisation

#### Circuit breaker
The solution of notificator component is enriched with circuit breaker pattern implementation.
https://martinfowler.com/bliki/CircuitBreaker.html

It let the notifier component to fail fast and use fallback strategies in case of any failures. 
The circuit breaker tracks availability of each transport and opens or closes the circuit depending of it's availability.
The pattern was implemented with use of php package called Ganesha (https://github.com/ackintosh/ganesha).
You can find more context about that decision in the one of ADR's located in */docs* catalog.

#### Multiple transports per channel
Each of notification channels can have multiple transports to deliver the notification. For example the email channel can use 
*Amazon SES* and as backup a *Sendgrid* service or even more. New transports can easily be added by implementing
Transport interface and configuring it in the services of notification module.

#### Feature flags (trade off - time limit)
Due to time limitations the app uses environment feature flags implementation. \
This limits configuration possibility of feature flags and reduce flexibility.

In serious project, some feature flags engine should be used instead.

#### MessagesContent storage (trade off - time limit, assumptions)
Due to time limitations the app uses in memory message storage, so there are only two ready to use messages you can send.\
This is the assumption made by me that in serious project messages will come from some external sources (microservice, database etc).
To not complicate implementation and be able to spent more time on notificator component, I've introduced the easiest form of messages storage - in memory.

#### Synchronous queue (trade-off - time limit)
Due to time limitations the synchronous symfony messenger transport is being used for handling commands and events.
Normally, the asynchronous transport should be used with some background workers or external message queue like RabbitMQ or Amazon SQS to gain better resistance and improve performance.
Currently, if sending the message to one of recipient will fail, the others also fails.

#### In-file logs (trade-off - time limit)
Currently all the application logs are stored in var/log directory and are not forward anywhere.
Normally some log tool should be used to handle them and provide reliable observability of the service.


## Future plans
Those are the things that I would do, if I would have more time and less assumptions to make:
* Use https://getunleash.io for handling feature flags instead of environmental implementation.
* Use asynchronous  message queue like RabbitMQ, Amazon SQS to introduce concurrency into sending and make app more failure resistant.
* Use Kibana, Grafana or some similar tool to handle logs and prepare availability dashboards.
* Use database, external service to fetch messages or accept content directly from outside contexts without fetching them from notificator app itself.
* Increase test coverage

## Authors

- [@michalludynia](https://github.com/michalludynia) (Michał Ludynia)

