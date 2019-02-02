.PHONY: test install help test-coverage fixture-test db-create
.DEFAULT_GOAL= help

CLASS=ALL
METHOD=ALL

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

composer.lock: composer.json
	composer update

vendor: composer.lock
	composer install

install: vendor ## Lance l'installation de l'application

db-create: install # Creer La bdd
	php ./bin/console doctrine:database:create
	php ./bin/console doctrine:schema:create

fixture-test: install ## Lance les fixtures de test
	php ./bin/console doctrine:fixture:load --group test --no-interaction

test: install fixture-test ## Lance phpUnit
	php ./bin/phpunit

test-coverage: install fixture-test ## Lance PhpUnit With Coverage HTML
	php ./bin/phpunit --coverage-html ./public/coverage/

test-filter: install fixture-test ## Lance tests with filter [CLASS=YourClassTest] [METHOD=testYourMethod]
	php ./bin/phpunit --filter $(CLASS)::$(METHOD)

go-travis: db-create test ## Make TravisCI Jobs
