.PHONY: rebuild build start stop copy-env-file enter runtest

APP_URL=`cat ./.env | grep APP_URL | cut -d'=' -f 2`

ifeq (,$(filter $(firstword $(MAKECMDGOALS)), start, stop, restart, kill, logs, run, enter, ))
  RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(RUN_ARGS):;@:)
endif

rebuild: stop
	@docker compose build --no-cache
	@printf "\e[44mAll containers refreshed and started!\e[m\n"

build: stop
	@docker compose build
	@printf "\e[93mContaines builed and started!\e[m\n"

copy-env-file:
	@cp ./.env ./.env.local || true

update-cron:
	docker exec -it php_env crontab -u root ./docker/cron/cron.txt
	docker exec -it php_env supervisorctl restart cron


test:
	@docker container stop php_queue_env
	@docker exec -it php_env php bin/console doctrine:database:drop --force --env=test || true
	@docker exec -it php_env php bin/console doctrine:database:create --env=test || true
	@docker exec -it php_env php bin/console doctrine:migrations:migrate -n --env=test || true
	@docker exec -it php_env php bin/console doctrine:fixtures:load -n --env=test || true
	@docker exec -it php_env php bin/phpunit
	@docker compose up -d

start:
	@docker compose up -d
	@printf "\e[92mApp url: $(APP_URL)\e[m\n"

stop:
	@docker compose stop
	@printf "\e[96mContainers stopped!\e[m\n"

enter:
	docker compose exec $(RUN_ARGS) /bin/bash

init: rebuild start
	docker exec -it php_env php bin/console import:company-symbols https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json