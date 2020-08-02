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

__General installation on production__

+ `git clone <this repo>`
+ `make build`
+ `make mongodb-install`
+ `make install`

__Commands__

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

__Dependencies__


