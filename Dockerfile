# Use Laravel Sail's PHP 8.2 image
FROM serversideup/php:8.2-fpm-nginx

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (for better caching)
COPY composer.json composer.lock ./

# Install dependencies (without running scripts)
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Copy application files
COPY . .

# Set proper permissions BEFORE running any Laravel commands
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Run composer scripts now that permissions are set
RUN composer run-script post-autoload-dump

# Generate optimized cache
RUN php artisan optimize

# Expose port 80
EXPOSE 80
