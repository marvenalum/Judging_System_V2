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
    nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Create storage structure and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage /var/www/html/bootstrap/cache -type d -exec chmod 755 {} \; \
    && find /var/www/html/storage /var/www/html/bootstrap/cache -type f -exec chmod 644 {} \;

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Pre-cache Laravel config and routes for faster startup
RUN php artisan config:cache && php artisan route:cache

# Create startup script
RUN cat > /start.sh << 'EOF'
#!/bin/sh
set -e
echo "Running database migrations..."
php artisan migrate --force || echo "Warning: Migration failed, but continuing startup"
echo "Starting nginx..."
service nginx start
echo "Starting PHP-FPM..."
exec php-fpm
EOF
RUN chmod +x /start.sh

# Expose ports
EXPOSE 8080

# Start services
CMD ["/start.sh"]
