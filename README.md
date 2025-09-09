# How to Run Project

1.  Clone this project

2.  Run Composer Install

`composer install`

3. Setup db pada file .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT={port_db_anda}
DB_DATABASE={nama_db_anda}
DB_USERNAME={user_db_anda}
DB_PASSWORD=
```

4. Generate app key

`php artisan key:generate`

5. Run Migration and Seed

`php artisan migrate --seed`

5. Generate JWT Key

`php artisan jwt:secret`

6. Run Project

`php artisan serve`

User Login:

```
 admin: admin@example.com
 verifikator: verif@example.com
 user: user@example.com

 pass: password
```
