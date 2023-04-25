TARGETS=src tests examples
PHP_STAN=php -d memory_limit=512M vendor/bin/phpstan analyse $(TARGETS) -c phpstan.neon --level 8
PHP_CS_SETTINGS=--standard=./vendor/gamee/php-code-checker-rules/ruleset.xml --extensions=php --ignore=tests/temp/*

.PHONY: cs
cs:
	vendor/bin/phpcs $(PHP_CS_SETTINGS) -sp $(TARGETS) --parallel=8

.PHONY: csfix
csfix:
	vendor/bin/phpcbf $(PHP_CS_SETTINGS) -sp $(TARGETS)

.PHONY: stan
stan:
	$(PHP_STAN)

.PHONY: test
test:
	./vendor/bin/phpunit --verbose tests
