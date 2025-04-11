FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    sqlite \
    sqlite-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip pdo_sqlite \
    && pecl install redis \
    && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Configure git to trust the directory
RUN git config --global --add safe.directory /var/www

# Create necessary directories
RUN mkdir -p /var/www/vendor \
    /var/www/storage/framework/views \
    /var/www/storage/framework/cache \
    /var/www/storage/framework/sessions \
    /var/www/storage/logs \
    /var/www/bootstrap/cache \
    /var/www/database \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/vendor \
    && chmod -R 777 /var/www/storage/framework /var/www/storage/logs

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install composer dependencies (including dev dependencies)
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy existing application directory
COPY . .

# Set proper permissions again after copying files
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/vendor \
    && chmod -R 777 /var/www/storage/framework /var/www/storage/logs

# Generate autoloader and run post-autoload-dump script with dev dependencies
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump

# Install and build frontend assets
RUN cd /var/www \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data /var/www/public/build

# Create SQLite database and set permissions
RUN touch /var/www/database/database.sqlite \
    && chown www-data:www-data /var/www/database/database.sqlite \
    && chmod 664 /var/www/database/database.sqlite

# Copy environment file and generate application key
RUN cp .env.docker .env \
    && php artisan key:generate --force \
    && php artisan config:clear \
    && php artisan cache:clear \
    && php artisan config:cache \
    && php artisan migrate --force

# Remove dev dependencies after everything is set up
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist \
    && composer dump-autoload --optimize --no-dev

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]