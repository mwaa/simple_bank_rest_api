## Simple Bank Rest Api (Laravel Demo 5.4)

Database storage is done using sqlite and middlewares used to 
showcase restrictions on how many transactions/amounts are allowed like a bank.

### Setup
1. Php needs to be installed. If not installed download [Laragon](https://laragon.org/)
2. Make sure  php7.1-sqlite3 driver is installed and enable pdo-sqlite in your php.ini file
3. To run code coverage make sure to install [Xdebug](https://xdebug.org/wizard.php) and edit php.ini to enable extension

### How to run
1. Clone the repo and run `composer install`. Installation instructions for [Composer](https://getcomposer.org/)
2. After creating `.env file` run `php artisan key:generate`
3. Run `php artisan serve` to serve application
4. Use a tool such as [Postman](https://www.getpostman.com/apps) to send http requests

Available End Points
 - localhost::8000/balance [GET]
 - localhost::8000/deposit [POST] 
    Sample Post Data - {"bank_account_id": 1, "amount":10001, "reason":"initial deposit"}
 - localhost::8000/withdraw [POST] Required in post data amount should numeric
    Sample Post Data - {"bank_account_id": 1, "amount":10001, "reason":"clear balance"}

### Execute tests 
Path `simple_bank_rest_api\tests\Http\ApiTest.php`
1. Run `vendor/bin/phpunit`
2. To get code coverage run `vendor\bin\phpunit --coverage-html tests\reports`. Open the folder and you can open
the `index.html file` on your browser to view how many lines of code are covered during testing.