FROM php:8.2-apache

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    libssl-dev pkg-config \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache config
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Working directory
WORKDIR /var/www/html

# Copy app (vendor/ already committed)
COPY . .

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Cache Laravel config for production
RUN php artisan config:cache --env=production || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

EXPOSE 80

CMD ["apache2-foreground"]
