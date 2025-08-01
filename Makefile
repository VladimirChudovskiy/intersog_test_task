PROJECT_NAME=intersog_test_task
SRC_DIR=src

.PHONY: up down restart install laravel migrate fresh-seed logs bash build test test-unit test-feature docs

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d

install:
	docker-compose run --rm app composer install

laravel:
	docker-compose run --rm app composer create-project laravel/laravel .

migrate:
	docker-compose exec app php artisan migrate

fresh-seed:
	docker-compose exec app php artisan migrate:fresh --seed

logs:
	docker-compose logs -f app

bash:
	docker-compose exec app bash

build:
	docker-compose build

test:
	docker-compose exec app php artisan test

test-unit:
	docker-compose exec app php artisan test --testsuite=Unit

test-feature:
	docker-compose exec app php artisan test --testsuite=Feature

docs:
	docker-compose exec app php artisan l5-swagger:generate
