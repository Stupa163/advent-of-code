composer:
	docker compose run php composer $(filter-out $@,$(MAKECMDGOALS))

phpunit:
	docker compose run php vendor/bin/phpunit