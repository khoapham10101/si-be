## Installation Project Shopinvest

1. Run ```mkdir data && docker-compose up -d```
2. Connect to php: ```docker-compose exec www bash``` and connect to database: ```docker-compose db www bash```
3. Run ```cp .env.example .env``` to copy env example file to env file and change configuration information for project
4. Run ```php artisan key:generate``` to create application key
5. Run ```composer update``` to install all package required
6. Run ```php artisan module:migrate``` to migrate table for database
7. Run ```php artisan module:seed UserStatus``` to seeding default data for default User Status table
8. Run ```php artisan module:seed Gender``` to seeding default data for default Gender table
9. Run ```php artisan module:seed Role``` to seeding default data for default Role table
10. Run ```php artisan module:seed User``` to seeding default data for default User table
