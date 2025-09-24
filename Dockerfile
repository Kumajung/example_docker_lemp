FROM php:8.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/php.ini $PHP_INI_DIR/conf.d/custom.ini

CMD ["php-fpm"]
