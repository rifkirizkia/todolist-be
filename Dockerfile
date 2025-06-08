# Dockerfile untuk Laravel dengan PHP-FPM & Composer

FROM php:8.1-fpm

# Install system dependencies dan PHP extensions yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libpng-dev libonig-dev libxml2-dev curl nginx \
    && docker-php-ext-install pdo_mysql zip mbstring exif pcntl bcmath gd

# Install composer secara manual
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Set working directory
WORKDIR /var/www/html

# Copy source code Laravel ke container
COPY . .

# Install dependencies Laravel lewat composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permission folder storage dan bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy konfigurasi nginx (buat file nginx.conf terpisah atau inline config)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Expose port 80 untuk nginx
EXPOSE 80

# Jalankan php-fpm dan nginx secara bersamaan (pakai supervisor atau script)
CMD service php8.1-fpm start && nginx -g "daemon off;"
