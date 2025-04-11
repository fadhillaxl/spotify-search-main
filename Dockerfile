FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Configure git to trust the directory
RUN git config --global --add safe.directory /var/www

# Create necessary directories
RUN mkdir -p /var/www/vendor /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/vendor

# Copy composer files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install composer dependencies (including dev dependencies)
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Copy existing application directory
COPY . .

# Set proper permissions again after copying files
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/vendor

# Generate autoloader and run post-autoload-dump script with dev dependencies
RUN composer dump-autoload --optimize \
    && composer run-script post-autoload-dump

# Install and build frontend assets
RUN cd /var/www \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data /var/www/public/build

# Copy environment file and generate application key
RUN cp .env.docker .env \
    && php artisan key:generate --force \
    && php artisan migrate --force

# Remove dev dependencies after everything is set up
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist \
    && composer dump-autoload --optimize --no-dev

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]