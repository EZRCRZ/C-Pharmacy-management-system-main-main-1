# Use official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    mariadb-client \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (18.x)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Set Node.js options to avoid memory issues
ENV NODE_OPTIONS="--max-old-space-size=512"

# Install Node.js dependencies and build assets
RUN npm install && \
    npm run production

# Copy .env file
COPY .env .env

# Generate Laravel app key
RUN php artisan key:generate

# Ensure storage and cache directories are writable
RUN chmod -R 777 storage bootstrap/cache

# Copy Nginx configuration
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Expose ports for Nginx and PHP-FPM
EXPOSE 80

# Start both PHP-FPM and Nginx
CMD service nginx start && php-fpm
