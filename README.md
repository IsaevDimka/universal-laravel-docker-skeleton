# Universal laravel docker skeleton

__Requirements__

+ Host OS — Win/Linux/UNIX/Mac
+ Docker

__🐳 Docker containers__

+ `php` — php:7.4-fpm | Laravel v7.12.0 | Socket.io 
+ `nginx` — nginx:1.19.0-alpine
+ `postgres` — postgres:12.3-alpine
+ `memcached` — memcached:1.6.6-alpine
+ `redis` — redis:6.0.5-alpine
+ `mongodb` — mongo:4.2.8
+ `adminer` — adminer:latest
+ `rabbitmq` — rabbitmq:3.8.5-management
+ `roadrunner` — php:7.4-cli & spiral/roadrunner v1.8.1
+ `clickhouse-server` — yandex/clickhouse-server


## Features

- Laravel 7
- Vue + VueRouter + Vuex + VueI18n + ESlint
- Pages with dynamic import and custom layouts
- Login, register, email verification and password reset
- Authentication with JWT
- Socialite integration
- Bootstrap 4 + Font Awesome 5

## Installation

+ `git clone <this repo>`
+ `make build`
+ `make mongodb-install`
+ `make install`

## Commands

Maintenance mode
``` shell
php artisan up
php artisan down --message="Upgrading Database" --allow=127.0.0.1 --retry=60
```

Clear OPcache:
``` bash
php artisan opcache:clear
```

Show OPcache config:
``` bash
php artisan opcache:config
```

Show OPcache status:
``` bash
php artisan opcache:status
```

Pre-compile your application code:
``` bash
php artisan opcache:compile {--force}
```
Note: `opcache.dups_fix `must be enabled, or use the `--force` flag.
If you run into "Cannot redeclare class" errors, enable `opcache.dups_fix` or add the class path to the exclude list.


Easily increment your version numbers, using Artisan commands
``` bash
php artisan version:major
php artisan version:minor
php artisan version:patch
php artisan version:commit
php artisan version:timestamp
```

## Socialite

This project comes with GitHub as an example for [Laravel Socialite](https://laravel.com/docs/5.8/socialite).

To enable the provider create a new GitHub application and use `https://example.com/api/oauth/github/callback` as the Authorization callback URL.

Edit `.env` and set `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET` with the keys form your GitHub application.

For other providers you may need to set the appropriate keys in `config/services.php` and redirect url in `OAuthController.php`.

## Dependencies


## License
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Isaev Dmitriy](https://isaevdimka.ru).


