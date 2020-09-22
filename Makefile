#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

include .env

docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)

cwd = $(shell pwd)

IP_ADDRESS = $(shell command wget -qO - eth0.me)
SHELL = /bin/bash
CURRENT_USER = $(shell id -u):$(shell id -g)

define print
	printf " \033[33m[%s]\033[0m \033[32m%s\033[0m\n" $1 $2
endef
define print_block
	printf " \e[30;48;5;82m  %s  \033[0m\n" $1
endef

.PHONY : help \
		 shell \
		 build up down restart logs cleanup
.SILENT : help up down shell cleanup
.DEFAULT_GOAL : help

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# --- [ Application ] -------------------------------------------------------------------------------------------------

---------------: ## ---------------

init: ## Make full application initialization (install, seed, build assets, etc)
	echo "testing"

install: mongodb-install app-install ## Installation

up: ## Create and start containers
	CURRENT_USER=$(CURRENT_USER) $(docker_compose_bin) up --detach
	$(call print_block, 'App                              ⇒ https://$(IP_ADDRESS):$(WEBSERVER_HTTPS_PORT)')
	$(call print_block, 'RoadRunner                       ⇒ https://$(IP_ADDRESS):$(ROADRUNNER_HTTPS_PORT)')
	$(call print_block, 'Adminer                          ⇒ http://$(IP_ADDRESS):$(ADMINER_PORT)')
	$(call print_block, 'Supervisor UI                    ⇒ http://$(IP_ADDRESS):$(APP_SUPERVISOR_PORT)')
	$(call print_block, 'RabbitMQ UI                      ⇒ http://$(IP_ADDRESS):$(RABBITMQ_MANAGEMENT_PORT)')
	$(call print_block, 'Additional ports (available for connections) - Redis ⇒ $(REDIS_PORT); Postgres ⇒ $(POSTGRES_PORT); Memcached ⇒ $(MEMCACHED_PORT); MongoDB ⇒ $(MONGO_PORT); RabbitMQ ⇒ $(RABBITMQ_EXCHANGE_PORT); ClickHouse ⇒ $(DB_CLICKHOUSE_PORT);')

down: ## Stop and remove containers, networks, images, and volumes
	$(docker_compose_bin) down -t 5

restart: down up ## Restart all containers

logs: ## Show docker logs
	$(docker_compose_bin) logs --follow

build: ## Build production mode
	$(docker_compose_bin) up -d --build

pull: ## pull from git
	git checkout -- .
	git pull

run-dev: ## Run dev app. Cron & horizon stop
	${docker_bin} exec -it $(DOCKER_PREFIX)-app sh -c "(cd /var/www/laravel && composer run-dev)"

app-shell: ## Start shell into app container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-app bash

app-install: ## Install app container
	${docker_bin} exec -it $(DOCKER_PREFIX)-app sh -c "(cd /var/www/laravel && composer app-install)"

app-update: ## Helper command deploy from git. Restart app container and re-build laravel
	${docker_bin} exec -it $(DOCKER_PREFIX)-app sh -c "(cd /var/www/laravel && php artisan down)"
	${docker_bin} exec -it $(DOCKER_PREFIX)-app sh -c "(cd /var/www/laravel && composer app-update)"
	docker container restart $(DOCKER_PREFIX)-app
	${docker_bin} exec -it $(DOCKER_PREFIX)-app sh -c "(cd /var/www/laravel && php artisan up)"

postgres-shell: ## Start shell into postgres container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-postgres bash

redis-shell: ## Start shell into redis container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-redis sh

nginx-shell: ## Start shell into nginx container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-nginx sh

memcached-shell: ## Start shell into memcached container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-memcached sh

adminer-shell: ## Start shell into adminer container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-adminer sh

mongodb-shell: ## Start shell into mongo container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-mongodb bash

clickhouse-shell: ## Start shell into clickhouse container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-clickhouse bash

rabbitmq-shell: ## Start shell into clickhouse container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-rabbitmq bash

mongodb-install: ## Install database mongodb
	${docker_bin} exec $(DOCKER_PREFIX)-mongodb mongo laravel_logs ./build/create-mongo-log-user.js -u admin -p $(MONGO_INITDB_ROOT_PASSWORD) --authenticationDatabase admin

socket-shell: ## Start shell into echo-server container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-echo-server sh
---------------: ##  ---------------

rr-shell: ## Start shell into RoadRunner container
	$(docker_compose_bin) exec $(DOCKER_PREFIX)-roadrunner bash

rr-reset-workers: ## Reset PHP workers in the container. (to reload your PHP source code)
	$(docker_bin) exec $(DOCKER_PREFIX)-roadrunner rr -c /etc/roadrunner/.rr.yaml http:reset

rr-show-workers: ## Show PHP workers' status
	$(docker_bin) exec $(DOCKER_PREFIX)-roadrunner rr -c /etc/roadrunner/.rr.yaml http:workers -i

rr-watch: ## Watch PHP source code change and reload PHP workers
	$(docker_bin) exec -d $(DOCKER_PREFIX)-roadrunner bash /var/www/roadrunner/watch.sh

getip: ## get IP address
	echo $(IP_ADDRESS)

---------------: ##  ---------------
