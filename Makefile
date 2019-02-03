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

install: vendor ## Lance l'installation de l'application

database-create: install ## Creer La bdd
	php ./bin/console doctrine:database:create
	php ./bin/console doctrine:schema:create

database-update: ## Update la Base de donnée
	php ./bin/console doctrine:schema:update

cc: cc-test cc-prod cc-dev ## Clear All Cache

cc-test: ## Clear Cache test
	rm -rf ./var/cache/test

cc-dev: ## Clear Cache dev
	rm -rf ./var/cache/dev

cc-prod: ## Clear Cache prod
	rm -rf ./var/cache/prod

fixture-test: install ## Lance les fixtures de test dans la bdd test
	php ./bin/console doctrine:fixture:load --group test --no-interaction --env test --purge-with-truncate

fixture: install ## Installe les fixture dans la bdd
	php ./bin/console doctrine:fixture:load --group devprod --no-interaction --purge-with-truncate

test: install fixture-test ## Lance phpUnit
	php ./bin/phpunit

test-coverage: install fixture-test ## Lance PhpUnit With Coverage HTML
	php ./bin/phpunit --coverage-html ./public/coverage/

test-filter: install fixture-test ## Lance tests with filter [CLASS=YourClassTest] [METHOD=testYourMethod]
	php ./bin/phpunit --filter $(CLASS)::$(METHOD)

behat: install fixture-test ## Lance les test fonctionnel Behat
	APP_ENV=test ./vendor/bin/behat

behat-filter: install fixture-test ## Run Behat test only with @filter tag
	APP_ENV=test ./vendor/bin/behat --tags @filter

go-travis: database-create test ## Make TravisCI Jobs
