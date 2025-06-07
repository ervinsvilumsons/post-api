# post-api

## Project setup
```
git clone https://github.com/ervinsvilumsons/post-api.git
cp .env.example .env
docker-compose up -d --build
```

## Tests
```
docker exec -it workspace /bin/bash
vendor/bin/phpunit --coverage-html coverage
```

