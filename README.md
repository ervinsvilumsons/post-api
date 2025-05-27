# post-api

## Project setup with Docker
```
cp .env.example .env
docker-compose up -d --build
```

## Project setup without Docker
```
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan serve --port=9001
```

