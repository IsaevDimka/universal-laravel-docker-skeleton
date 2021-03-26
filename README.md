# Universal laravel docker skeleton

[![Changelog badge][changelog-badge]][changelog] 
[![Version 1.2.1 Badge][version-badge]][changelog] 
[![MIT License Badge][license-badge]][license]
![Docker build CI](https://github.com/IsaevDimka/universal-laravel-docker-skeleton/workflows/Docker%20build%20CI/badge.svg)
![Laravel](https://github.com/IsaevDimka/universal-laravel-docker-skeleton/workflows/Laravel/badge.svg)
![Node.js CI](https://github.com/IsaevDimka/universal-laravel-docker-skeleton/workflows/Node.js%20CI/badge.svg)

## Introduction

## Requirements

+ Host OS — Win/Linux/UNIX/Mac
+ Docker

## 🐳 Docker containers

+ `php` — php:7.4.15-fpm | Laravel 8.x.x
+ `laravel-echo-server` — node:15-alpine + laravel-echo-server
+ `nginx` — nginx:1.19.6-alpine
+ `postgres` — postgres:13.2-alpine
+ `memcached` — memcached:1.6.9-alpine
+ `redis` — redis:6.0.10-alpine
+ `mongodb` — mongo:4.4.3
+ `adminer` — adminer:latest
+ `rabbitmq` — rabbitmq:3.8.11-management-alpine
+ `roadrunner` — php:8.0.2-cli & spiral/roadrunner v1.9.2
+ `clickhouse-server` — yandex/clickhouse-server 20.8.12.2

## Features

- Laravel 8
- Vue + VueRouter + Vuex + VueI18n + ESlint
- Pages with dynamic import and custom layouts
- Login, register, email verification and password reset
- Authentication with JWT
- Socialite integration support drivers: github, google, facebook, telegram, vkontakte, twitter, gitlab, zalo, bitbucket, yandex
- Element UI for backend
- Universal api facade
- Helper DebugService
- Telegram webhook support
- Helper OpcacheService

## Installation

+ `git clone <this repo>`
+ `make build`
+ `make install`

__Quick setup for local develop__

+ set `./docker-containers/app/opcache.ini` values `*enabled*` to `0` and save file.

+ `php -d memory_limit=-1 /usr/local/bin/composer install`

## Commands

Maintenance mode
``` shell
php artisan up
php artisan down
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

## Perf

__phpstan analyse:__
`./linter.sh`
`./phpstan.sh`

## Dependencies

## Contribute

Please do contribute! Issues and pull requests are welcome.

Thank you for your help to improving software one changelog at a time!

## License
MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [IsaevDimka](https://github.com/IsaevDimka).

[version-badge]: https://img.shields.io/badge/stable-1.2.1-blue.svg
[changelog]: ./CHANGELOG.md
[changelog-badge]: https://img.shields.io/badge/changelog-docker%20skeleton-%23E05735
[license]: ./LICENSE
[license-badge]: https://img.shields.io/badge/license-MIT-blue.svg
