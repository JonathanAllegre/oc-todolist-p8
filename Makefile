.PHONY: test install help test-coverage fixture-test database-create database-update go-travis fixture cc-test
.DEFAULT_GOAL= help

CLASS=ALL
METHOD=ALL
ENV=DEV

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

composer.lock: composer.json
	composer update

vendor: composer.lock
	composer install

install: vendor ## Launch install

database-create: install ## Create DB
	php ./bin/console doctrine:database:create
	php ./bin/console doctrine:schema:create

database-update:database-update-test ## Update DB
	php ./bin/console doctrine:schema:update --force --env dev

database-update-test: ## Update Test DB
	php ./bin/console doctrine:schema:update --force --env test

cc: cc-test cc-prod cc-dev ## Clear All Cache

cc-test: ## Clear Cache test
	rm -rf ./var/cache/test

cc-dev: ## Clear Cache dev
	rm -rf ./var/cache/dev

cc-prod: ## Clear Cache prod
	rm -rf ./var/cache/prod

fixture-test: install ## Test Fixtures
	php ./bin/console doctrine:fixture:load --group test --no-interaction --env test

fixture: install ## Dev & Prod Fixtures
	php ./bin/console doctrine:fixture:load --group devprod --no-interaction

test: install fixture-test ## Run PHPUnit
	php ./bin/phpunit

test-coverage: install fixture-test ## Run PHPUnit With Coverage
	php ./bin/phpunit --coverage-html ./public/coverage/

test-filter: install fixture-test ## Run PHPUnit With Filter [CLASS=YourClassTest] [METHOD=testYourMethod]
	php ./bin/phpunit --filter $(CLASS)::$(METHOD)

go-travis: database-create test ## Make TravisCI Jobs
