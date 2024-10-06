# Use a PHP 8.1 FPM
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy Laravel project files to the container
COPY . /var/www/

# Set the working directory to the Laravel project root
WORKDIR /var/www

# Copy the .env.example to .env
RUN cp .env.example .env

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev

# Set permissions for Laravel storage directory
RUN chmod -R 777 storage

# Generate Laravel application key
RUN php artisan key:generate
