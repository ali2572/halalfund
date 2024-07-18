#setUp App for fisrt Time
setup:
	@make build
	@make up 
	@make composer-update
#build contaners & images
build:
	docker-compose build
#Stop all containers	
stop:
	docker-compose stop
#up all containers
up:
	docker-compose up -d
#update composer psckeges	
composer-update:
	docker exec laravel_halalfund bash -c "cd /var/www/html/task_laravel; composer update"
#migrate && set data from seeders	
set_data:
	docker exec laravel_halalfund bash -c "cd /var/www/html/task_laravel; php artisan migrate"
	docker exec laravel_halalfund bash -c "cd /var/www/html/task_laravel; php artisan db:seed"
#start App on localhost:9000 
run_App:
	docker exec laravel_halalfund bash -c "cd /var/www/html/task_laravel; php artisan serve --host=0.0.0.0 --port=8080"
#run future && Unit tests
test:
	docker exec laravel_halalfund bash -c "cd /var/www/html/task_laravel; php artisan test"
#open docker contaner ssh
openSSh:
	docker exec -it laravel_halalfund bash


