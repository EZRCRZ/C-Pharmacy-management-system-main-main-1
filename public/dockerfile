# Use an official PHP image with Composer and Node.js
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    mariadb-client \
    && docker-php-ext-install pdo_mysql gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm install && npm run prod

# Copy .env file
RUN cp .env.example .env

# Generate app key
RUN php artisan key:generate

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
