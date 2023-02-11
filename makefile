install:
	docker-compose run php8 sh -c "composer install"

scrape:
	docker-compose run php8 sh -c "bin/console scrape:items"

run:
	docker-compose run php8 sh -c "bin/console $(command)"

command:
	docker-compose run php8 sh -c "$(command)"

lint:
	docker-compose run php8 sh -c "php -l -d display_errors=On src/*"
	docker-compose run php8 sh -c "phpcs --report=full -w --standard=PSR2 ./src"

.PHONY: tests
tests:
	docker-compose run php8 sh -c "./vendor/bin/phpunit tests"
