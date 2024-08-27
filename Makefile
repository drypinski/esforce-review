init: docker-pull docker-build docker-up site-init

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
site-init: site-clean site-composer-install site-cache-clear

site-clean:
	docker compose run --rm site-php-cli sh -c 'rm -rf vendor'
	docker compose run --rm site-node sh -c 'rm -rf node_modules'

site-composer-install:
	COMPOSER_MEMORY_LIMIT=-1 docker compose run --rm site-php-cli sh -c 'composer install --no-interaction --no-scripts'

site-cache-clear:
	docker compose run --rm site-php-cli sh -c 'bin/console cache:clear && bin/console cache:warmup'

site-yarn-install:
	docker compose run --rm site-node sh -c 'yarn install'

site-yarn-build:
	docker compose run --rm site-node sh -c 'yarn run build'

site-run-php:
	docker compose run --rm site-php-cli bash

# =================
# === FRONT =======
# =================
front-init: front-clean front-install front-ready

front-install:
	docker compose run --rm front-node sh -c 'yarn install'

front-clean:
	docker compose run --rm front-node sh -c 'rm -rf .ready .nuxt node_modules'

front-ready:
	docker compose run --rm front-node sh -c 'touch .ready'

front-run-node:
	docker compose run --rm front-node bash
