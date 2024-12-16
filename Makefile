.PHONY: test

test:
	@./vendor/bin/pest

DIRECTORIES = app tests

PHPCS = ./vendor/bin/phpcs

PHPCBF = ./vendor/bin/phpcbf

.PHONY: phpcs
phpcs:
	@echo "Running PHP_CodeSniffer..."
	$(PHPCS) $(DIRECTORIES)

.PHONY: phpcbf
phpcbf:
	@echo "Running PHP_CodeBeautifier and Fixer..."
	$(PHPCBF) $(DIRECTORIES)

.PHONY: fix-and-check
fix-and-check: phpcbf phpcs
	@echo "Fixing and checking code standards completed."

# Default target
.PHONY: all
all: phpcs
