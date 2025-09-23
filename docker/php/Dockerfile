FROM php:8.2-fpm-alpine

# Install extensions required by the application.
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Use production php.ini as a baseline and enable useful development defaults.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY php.ini $PHP_INI_DIR/conf.d/custom.ini

CMD ["php-fpm"]