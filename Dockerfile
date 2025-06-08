FROM php:8.1-fpm

# Install dependencies, ekstensi Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Set permission storage & bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
