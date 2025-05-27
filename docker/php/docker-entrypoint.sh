#!/bin/sh

echo "Waiting for MySQL..."

while ! nc -z $DB_HOST $DB_PORT; do
  echo "MySQL is down"
  sleep 2
done

echo "MySQL is up"

php artisan migrate --force
php artisan db:seed

exec "$@"