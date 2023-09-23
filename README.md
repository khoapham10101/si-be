# Shopinvest API
## Installation

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
11. Run ```php artisan storage:link```  to save files for public

## Shopinvest Run Tests
This project uses PHPUnit for running tests.

### Run All Tests

To run all tests in your application, simply use the following command:

```bash
vendor/bin/phpunit
```

### Run Tests for a Specific File

#### Test a full test file:

```bash
vendor/bin/phpunit --filter 'WishlistControllerTest' 
```

If you want to only run a single test method, class or module you can use the filter flag:

```bash
vendor/bin/phpunit --filter 'test_user_can_get_list_wishlist'
```


