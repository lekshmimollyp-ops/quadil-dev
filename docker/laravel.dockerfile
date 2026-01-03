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

# Set working directory
WORKDIR /var/www

# Build arguments
ARG SERVICE_NAME

# Create a non-root user (optional but good practice, skipping for simplified dev demo)

# 1. Copy composer dependencies first for caching
COPY composer.json composer.lock ./

# 2. Install dependencies (include dev deps for Faker/Seeding in demo)
RUN composer install --no-scripts --no-autoloader --prefer-dist

# 3. Copy application code
COPY . .

# 4. Generate autoloader and run scripts
RUN composer dump-autoload --optimize

# 5. Fix permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port (default 9000 for FPM, but we might want 8000 for `php artisan serve` in dev mode? 
# For prod, we usually use Nginx + FPM. 
# SIMPLIFICATION FOR DEMO: Use `php artisan serve` to avoid setting up Nginx for 14 services.
# It's not "prod" prod, but it's fine for a team demo.
CMD php artisan serve --host=0.0.0.0 --port=8000
EXPOSE 8000
