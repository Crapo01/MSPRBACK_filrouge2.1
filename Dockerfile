FROM php:8.1-apache

# Install necessary extensions and dependencies
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

# Copy application files
COPY . /var/www/html

# Expose Apache Port
EXPOSE 80

