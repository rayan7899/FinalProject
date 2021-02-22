مشروع التخرج - مجموعة B

Installation
```sh
sudo apt update
sudo apt install composer  
composer install

npm install && npm run dev
```

Change username function in ./vendor/laravel/ui/auth-backend/AuthenticatesUsers.php

from 
```php
 public function username()
    {
        return 'email';
    }

```
to

```php
public function username()
    {
        return 'national_id';
    }

```
To reset database run
```sh
php artisan migrate:fresh --seed
```