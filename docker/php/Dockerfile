FROM php:8.3-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql zip

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# working dir
WORKDIR /var/www/html

# copy composer files first (for caching)
COPY composer.json composer.lock ./

# install dependencies
RUN composer install --no-interaction --prefer-dist --no-scripts

# install XDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# copy project AFTER dependencies
COPY . .

RUN composer dump-autoload --optimize

# PHP config
COPY ./docker/php/conf.d /usr/local/etc/php/conf.d/

EXPOSE 9000

CMD ["php-fpm"]