FROM php:8.1-apache

# Install required extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install MongoDB PHP extension
RUN pecl install mongodb \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install project dependencies
RUN composer install --no-dev --optimize-autoloader || true

# Expose Apache Port
EXPOSE 80

