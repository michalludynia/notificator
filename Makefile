up:
	@docker-compose up -d

down:
	@docker-compose down

logs:
	@docker-compose logs -f php

bash:
	@docker-compose exec php sh

cs-fix:
	@docker-compose ${API_COMPOSE} exec php ./vendor/bin/php-cs-fixer fix --config standards/.php-cs-fixer.dist.php

phpstan:
	@docker-compose ${API_COMPOSE} exec php ./vendor/bin/phpstan analyse -c standards/phpstan.neon

test-unit:
	@docker-compose ${API_COMPOSE} exec php ./vendor/bin/phpunit --testsuite unit

test-business:
	@docker-compose ${API_COMPOSE} exec php ./vendor/bin/behat