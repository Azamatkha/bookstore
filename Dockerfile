FROM php:8.4-cli

# System packages
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Install Node deps & build
RUN npm install --legacy-peer-deps && npm run build

# Laravel optimize
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Start server
CMD php artisan serve --host=0.0.0.0 --port=$PORT