##API

Please follow the guide.

1. `git clone`
2. `update the .env file along with database connection`
3. `composer install && composer update`

```
APP_URL=http://localhost:8000
```

Open an terminal window and copy paste the following command

```
php artisan serve
```

Use postman or browser to call the below API
```
URL : http://localhost/TEST-API/test?username=demo&password=pwd1234&purchase_order_ids=2344
```