# Use a PHP base image with Apache
FROM php:8.1-apache

# Install dependencies required for Composer and your PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy your composer.json and composer.lock to the container
COPY composer.json composer.lock /var/www/html/

# Run composer install to install dependencies
RUN composer install --no-interaction --prefer-dist

# Copy the rest of your project files to the container
COPY ./www /var/www/html

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]


