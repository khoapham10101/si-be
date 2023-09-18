## Installation Project Shopinvest

1. Run ```cp .env.example .env``` to copy env example file to env file and change configuration information for project
2. Run ```php artisan key:generate``` to create application key
3. Run ```composer update``` to install all package required
4. Run ```php artisan module:migrate``` to migrate table for database
5. Run ```php artisan module:seed UserStatus``` to seeding default data for default User Status table
6. Run ```php artisan module:seed Gender``` to seeding default data for default Gender table
7. Run ```php artisan module:seed Role``` to seeding default data for default Role table
8. Run ```php artisan module:seed User``` to seeding default data for default User table
