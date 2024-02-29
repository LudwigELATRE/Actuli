
compose=docker-compose -f docker-compose.yaml
console= bin/console
phpunit= vendor/bin/phpunit
php = php

include common.mk

.PHONY: cache-clear
cache-clear: ## clear cache
		$(console) cache:clear

.PHONY: start
start: ## start app
		$(php) -S localhost:8000 -d display errors=1

.PHONY: start-db
start-db: ## start database
		$(compose) up -d

.PHONY: reset-db
reset-db: ## reset database
		$(console) doctrine:database:drop --force
		$(console) doctrine:database:create
		$(console) doctrine:migrations:migrate

.PHONU: start-tests
start-tests: ## start tests
		$(phpunit) tests/FactureElectronique/ --exclude-group ignore
