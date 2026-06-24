#!/bin/sh
set -e

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

if [ ! -f /var/www/html/vendor/autoload.php ]; then
    echo "Vendor нет, running composer install..."
    composer install --no-scripts --no-autoloader
    composer dump-autoload --optimize
else
    echo "Vendor already exists, skipping install"
fi

if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
        echo ".env создан из .env.example"
    else
        echo ".env и .env.example не были найдены"
    fi
fi

if ! grep -q "^APP_KEY=" /var/www/html/.env || [ -z "$(grep '^APP_KEY=' /var/www/html/.env | cut -d= -f2-)" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
    echo "Application key was generated"
else
    echo "APP_KEY already set"
fi

if [ ! -f /var/www/html/vendor/predis/predis/composer.json ]; then
    echo "Installing predis/predis..."
    composer require predis/predis
fi

php artisan config:clear

if [ ! -f /var/www/html/.migrated ]; then
    echo "Миграции запущены"
    php artisan migrate --force
    echo "Миграции успешно завершены"
    php artisan db:seed --force
    echo "Сидеры успешно завершены"

    touch /var/www/html/.migrated
else
    echo "Миграции уже проводились, пропускаю"
fi

exec "$@"
