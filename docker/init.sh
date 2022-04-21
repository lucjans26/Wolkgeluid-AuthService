#!/bin/sh

cd /var/www

php artisan migrate:fresh
php artisan cache:clear
php artisan route:cache

/usr/bin/supervisord -c /etc/supervisord.conf
