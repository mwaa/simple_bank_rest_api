## Simple Bank Rest Api (Laravel Demo 5.4)

Database storage is done using sqlite and middlewares used to 
showcase restrictions on how many transactions/amounts are allowed like a bank.


### How to run
1. Make sure  php7.1-sqlite3 driver is installed.
2. Clone the repo and after creating `.env file` run `php artisan key:generate`
3. Run `php artisan serve` to serve application
4. Use a tool such as [Postman](https://www.getpostman.com/apps) to send http requests

Available End Points
 - localhost::8000/balance [GET]
 - localhost::8000/deposit [POST] Required in post data is amount should numeric
 - localhost::8000/withdraw [POST] Required in post data amount should numeric
