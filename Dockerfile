# Base image
FROM php:8.1-fpm

ARG PORT=8080
ENV PORT=${PORT}

# Install dependencies
RUN apt-get update && apt-get install -y \
    # Install Nginx
    nginx \  
    # Librerie PostgreSQL
    libpq-dev \  
    procps \
    nano \
    git \
    unzip \
    # Questo installa envsubst
    gettext-base \  
    supervisor \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP-FPM to listen on 127.0.0.1:9000
RUN sed -i 's/listen = .*/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

# Copy configuration files
COPY nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port
EXPOSE ${PORT}

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
