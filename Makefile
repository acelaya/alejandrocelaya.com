default:
	@echo "Usage: make <command>"
	@echo ""
	@echo "Available commands:"
	@echo "  COMPOSER:"
	@echo "    composer_selfupdate    Updates composer binary"
	@echo "    composer_update        Install composer dependencies"
	@echo "    dumpautoload           Regenerate autoloader"
	@echo ""
	@echo "  TOOLS:"
	@echo "    phpunit                Run PHPunit tests and create code coverage reports in clover format"
	@echo "    phpunit_html           Run PHPunit tests and create code coverage reports in HTML format"
	@echo "    phpcs                  Check PHP code styles with code sniffer (PSR-2)"

composer_selfupdate:
	composer self-update

composer_update: composer_selfupdate
	composer update;

dumpautoload:
	composer dump-autoload

phpunit:
	./vendor/bin/phpunit -c tests/phpunit.xml --coverage-clover build/clover.xml

phpunit_html:
	./vendor/bin/phpunit -c tests/phpunit.xml --coverage-html build/coverage

phpcs:
	./vendor/bin/phpcs --standard=PSR2 ./src/ ./tests/ ./config ./public/index.php
