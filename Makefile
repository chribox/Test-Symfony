.PHONY: up down restart shell php mysql nginx logs build

up:
	docker-compose up -d

down:
	docker-compose down

restart:
	docker-compose down && docker-compose up -d --build

build:
	docker-compose build

logs:
	docker-compose logs -f

php:
	docker exec -it php bash

mysql:
	docker exec -it $$(docker ps -qf "ancestor=mysql:8.4") bash

nginx:
	docker exec -it $$(docker ps -qf "ancestor=nginx:latest") bash

shell:
	docker exec -it $$(docker-compose ps -q php) bash

