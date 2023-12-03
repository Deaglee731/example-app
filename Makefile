# include .env
# export

env:
	@docker-compose exec --user=www-data app bash

env-root:
	@docker-compose exec --user=root app bash

up:
	@docker-compose up -d --build --remove-orphans

down:
	@docker-compose down

down-v:
	@docker-compose down -v

install: up composer-install key-generate twill-build npm-install npm-dev

composer-install:
	@docker-compose exec --user=www-data app composer install

key-generate:
	@docker-compose exec --user=www-data app artisan key:generate

