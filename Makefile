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

## Update composer
composer-update:
	$(EXEC_PHP) composer update

## Install composer
composer-install:
	$(EXEC_PHP) composer install

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

## Snapshot Dump the database
db-dump: wait-db
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources -h database -U pedro > dump/ressources.sql'

## Regenerate dump database
db-regenerate-dump: db-drop db-create db-migrate
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources -h database -U pedro > dump/ressources.sql'

## Reload Database from dump @see db-regenerate-dump
db-reload: db-drop db-create
	$(EXEC_PHP) sh -c 'PGPASSWORD="P@ss02468*" pg_dump ressources -h database -U pedro > dump/ressources.sql'

## Generate Doctrine Migration Diff
db-diff:
	$(EXEC_SYMFONY) doctrine:migration:diff --no-interaction

## Execute Doctrine Migration
db-migrate:
	$(EXEC_SYMFONY) doctrine:migration:migrate --no-interaction

## Execute Doctrine Migration Status
db-status:
	$(EXEC_SYMFONY) doctrine:migration:status --no-interaction


#################################
Code_quality_and_security:

## CS fixer
cs-fixer:
	$(EXEC_PHP) ./vendor/bin/php-cs-fixer fix --dry-run --diff