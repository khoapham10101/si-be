build:
	mkdir -p data
	cp .env.example .env
	docker-compose up -d --build
	docker-compose exec www bash -c "composer install"
	docker-compose exec www bash -c "php artisan key:generate"
	docker-compose exec db  bash -c 'mysql -uroot -proot -e "DROP DATABASE IF EXISTS si;"'
	docker-compose exec www bash -c "php artisan migrate --force"
	docker-compose exec www bash -c "php artisan db:seed"
	docker-compose exec www bash -c "php artisan storage:link"

up:
	
	docker-compose up -d

down:
	docker-compose down

ps:
	docker-compose ps

stop:
	docker-compose stop

start:
	docker-compose start

restart:
	docker-compose restart

php:
	docker-compose exec www bash

db:
	docker-compose exec db bash

test:
	docker-compose exec db bash -c 'mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS shopinvest_testing;" && mysql -u root -proot shopinvest_testing < /docker-entrypoint-initdb.d/shopinvest_test.sql'
	docker-compose exec www bash -c "/var/www/html/vendor/bin/phpunit"
	docker-compose exec db bash -c 'mysql -uroot -proot -e "drop database shopinvest_testing;"'