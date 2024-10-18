# Base image
FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    procps \
    nano \
    git \
    unzip \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy configuration files
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install Composer (se necessario)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (se usi Composer)
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port
EXPOSE 8080

# Start PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
