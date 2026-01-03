FROM php:8.3-fpm-alpine

# Install system dependencies + Node.js/NPM
RUN apk add --no-cache \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    oniguruma-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy code
COPY . .

# 1. Install PHP Dependencies FIRST (This creates 'vendor/tightenco/ziggy' which frontend needs)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN composer dump-autoload --optimize

# Fix permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 2. Install Node Dependencies & Build Assets
# using --legacy-peer-deps for Vite 7 compatibility
RUN npm ci --legacy-peer-deps
RUN npm run build

CMD php artisan serve --host=0.0.0.0 --port=8080
EXPOSE 8080
