init: init-ci front-ready
init-ci: docker-down-clear \
	site-clear front-clear \
	docker-pull docker-build docker-up \
	site-init front-init

up: docker-up
down: docker-down
restart: down up

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

logs:
	docker compose logs -f

docker-build:
	docker compose build --pull

php: site-run-php
node: front-run-node

# =================
# === SITE ========
# =================
site-init: site-update-permissions site-composer-install site-wait-db site-migrations-migrate site-cache-clear

site-clear:
	docker run --rm -v ${PWD}/site:/app -w /app alpine sh -c 'rm -rf vendor/* var/*'

site-update-permissions:
	docker run --rm -v ${PWD}/site:/app -w /app alpine sh -c 'mkdir -p var && chmod a+w -R var'

site-composer-install:
	COMPOSER_MEMORY_LIMIT=-1 docker compose run --rm site-php-cli sh -c 'composer install --no-interaction --no-scripts'

site-wait-db:
	docker compose run --rm site-php-cli wait-for-it site_db:3306 -t 30

site-migrations-migrate:
	docker compose run --rm site-php-cli sh -c 'bin/console do:mi:mi --no-interaction'

site-cache-clear:
	docker compose run --rm site-php-cli sh -c 'bin/console cache:clear && bin/console cache:warmup'

site-run-php:
	docker compose run --rm site-php-cli bash

# =================
# === FRONT =======
# =================
front-init: front-install

front-clear:
	docker compose run --rm front-node sh -c 'rm -rf .ready .nuxt .output node_modules'

front-install:
	docker compose run --rm front-node sh -c 'yarn install'

front-ready:
	docker run --rm -v ${PWD}/front:/app -w /app alpine touch .ready

front-run-node:
	docker compose run --rm front-node bash
