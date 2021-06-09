##Import JSON-file:	"challenge.json

The command to import file is DataImportCommand.
it can run from the console 
```php 
artisan data:import challenge.json
``` 
or from schedule

#### Installation (Without Docker)

1 .From the project directory
```
composer install 
copy .emv.example .env
```

2. Setup Database (Not request with docker)
Update .env file
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=testapp
DB_USERNAME=root
DB_PASSWORD=secret
````


3. Run Data Migration
```
php artisan migrate
```


#### Installation with Docker

Run docker compose command

```
docker compose up
```

From  test-app-www container,  Run 

```
php artisan migrate
```

```php 
artisan data:import challenge.json
``` 
 

