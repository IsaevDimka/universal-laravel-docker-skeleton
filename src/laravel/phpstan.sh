#!/bin/bash

vendor/bin/phpstan analyse | tee /var/www/laravel/storage/logs/phpstan.txt
