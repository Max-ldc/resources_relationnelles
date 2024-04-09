DOCKER_COMPOSE = docker compose
EXEC_PHP = $(DOCKER_COMPOSE) exec -u www-data php
BASH_PHP = $(DOCKER_COMPOSE) exec -u www-data php bash
EXEC_SYMFONY = $(EXEC_PHP) bin/console

#################################
Docker:

## Install project
install: build up composer-install db-reload

## Reset environment
reset: down install

## Build containers
build:
	$(DOCKER_COMPOSE) build

## Up environment
up:
	$(DOCKER_COMPOSE) up -d

## Down environment
down:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) down --remove-orphans
	$(DOCKER_COMPOSE) down -v

## Show logs
logs:
	$(DOCKER_COMPOSE) logs -f --tail 0

#################################
Project:

## Update composer
composer-update:
	$(EXEC_PHP) composer update

## Install composer
composer-install:
	$(EXEC_PHP) composer install

## Run phpunit tests
unit-test:
	$(EXEC_PHP) bin/phpunit --testsuite Unit

## Run API tests
api-test: env-test db-reload-test
	$(EXEC_PHP) php -dmemory_limit=512M bin/phpunit --testsuite Integration
	$(MAKE) env-dev

## Switch Environment to test
env-test:
	@echo "Switch to ${YELLOW}test${RESET}"
	@-$(EXEC_PHP) bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=test" >> .env.local'
	@-$(EXEC_PHP) sed -i 's/APP_ENV=.*/APP_ENV=test/g' .env.local

## Switch Environment to dev
env-dev:
	@echo "Switch to ${YELLOW}dev${RESET}"
	@-$(EXEC_PHP) bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=dev" >> .env.local'
	@-$(EXEC_PHP) sed -i 's/APP_ENV=.*/APP_ENV=dev/g' .env.local

MinIO:

minio-fixtures-dev: env-dev
	$(EXEC_SYMFONY) app:upload-files

#################################
Database:

wait-db:
	@$(EXEC_PHP) php -r "set_time_limit(60);for(;;){if(@fsockopen('database',5432))die;echo \"\";sleep(1);}"

## Create Database
db-create: wait-db
	$(EXEC_SYMFONY) doctrine:database:create --if-not-exists

## Drop Database
db-drop: wait-db
	$(EXEC_SYMFONY) doctrine:database:drop --force --if-exists

## Validate if the schema is sync with mapping files
db-validate: db-reload
	$(EXEC_SYMFONY) doctrine:schema:validate

## Load Data fixtures on Database
db-fixtures:
	$(EXEC_SYMFONY) doctrine:fixtures:load --no-interaction

## Snapshot Dump the database
db-dump: wait-db
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources -h database -U pedro > dump/ressources.sql'

## Regenerate dump database
db-regenerate-dump: db-drop db-create db-migrate db-fixtures
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources -h database -U pedro > dump/ressources.sql'

## Reload Database from dump @see db-regenerate-dump
db-reload: db-drop db-create
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" psql ressources -h database -U pedro < dump/ressources.sql'

## Generate Doctrine Migration Diff
db-diff:
	$(EXEC_SYMFONY) doctrine:migration:diff --no-interaction

## Execute Doctrine Migration
db-migrate:
	$(EXEC_SYMFONY) doctrine:migration:migrate --no-interaction

## Execute Doctrine Migration Status
db-status:
	$(EXEC_SYMFONY) doctrine:migration:status --no-interaction

## Create Database Test
db-create-test: wait-db
	$(EXEC_SYMFONY) doctrine:database:create --if-not-exists --env test

## Drop Database Test
db-drop-test: wait-db
	$(EXEC_SYMFONY) doctrine:database:drop --force --if-exists --env test

## Execute Doctrine Migration Test
db-migrate-test:
	$(EXEC_SYMFONY) doctrine:migration:migrate --no-interaction --env test

## Load Data fixtures on Database
db-fixtures-test:
	$(EXEC_SYMFONY) doctrine:fixtures:load --no-interaction --env test

## Regenerate dump test database
db-regenerate-dump-test: db-drop-test db-create-test db-migrate-test db-fixtures-test
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources_test -h database -U pedro > dump/ressources_test.sql'

## Reload Test Database from dump @see db-regenerate-dump-test
db-reload-test: db-drop-test db-create-test
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" psql ressources_test -h database -U pedro < dump/ressources_test.sql'

#################################
Code_quality_and_security:

## CS fixer
cs-fixer:
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --dry-run --diff

## CS fixer apply
cs-fixer-apply:
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --diff