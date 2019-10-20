#!/bin/bash
cd /var/www/fenix

touch storage/logs/laravel.log && chmod 775 storage/logs/laravel.log && chown www-data storage/logs/laravel.log

composer install && php artisan key:generate

sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=pgsql/g' .env
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=0.0.0.0/g' .env
sed -i 's/DB_PORT=3306/DB_PORT=5432/g' .env
sed -i 's/DB_DATABASE=homestead/DB_DATABASE=fenix/g' .env
sed -i 's/DB_USERNAME=homestead/DB_USERNAME=fenix/g' .env
sed -i 's/DB_PASSWORD=secret/DB_PASSWORD=123456/g' .env

php artisan migrate:refresh && echo "Done..."
