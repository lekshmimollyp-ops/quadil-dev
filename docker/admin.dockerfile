# Stage 1: Build Frontend Assets
FROM node:20 as frontend
WORKDIR /app
COPY package*.json ./
# Clean install ensuring strict version matching
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Serve Application
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy code
COPY . .

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build /var/www/public/build

# Install dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN composer dump-autoload --optimize

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080
