CONTAINER=rip-php

up:
	docker-compose up -d

down:
	docker-compose down

composer:
	docker exec -it $(CONTAINER) composer $(filter-out $@, $(MAKECMDGOALS))

check: cs analyse

analyse:
	docker exec -it $(CONTAINER) vendor/bin/phpstan analyse

cs:
	docker exec -it $(CONTAINER) vendor/bin/phpcs

csfix:
	docker exec -it $(CONTAINER) vendor/bin/phpcbf

ssh:
	docker exec -it $(CONTAINER) /bin/bash

rip:
	docker exec -it $(CONTAINER) bin/rip random:issue

%:
	@true
