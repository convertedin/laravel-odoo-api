#!/bin/bash

php7=$(docker build docker/php7.4 -q)
docker run --rm  -v $(pwd):/app "$php7" php /app/vendor/bin/phpunit --configuration /app/phpunit.xml

php8=$(docker build docker/php8 -q)
docker run --rm  -v $(pwd):/app "$php8" php /app/vendor/bin/phpunit --configuration /app/phpunit.xml