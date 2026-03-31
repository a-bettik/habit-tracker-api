# system deps
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# copy project
COPY . .

RUN composer install

EXPOSE 9000

CMD ["php-fpm"]