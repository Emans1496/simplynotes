# Usa una base di PHP-FPM
FROM php:8.1-fpm

# Installa Nginx e le dipendenze necessarie
RUN apt-get update && apt-get install -y \
    nginx \
    gettext-base \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_pgsql

# Copia i file della tua applicazione nella directory di lavoro del container
COPY . /var/www/html

# Copia la configurazione Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.template

# Copia il file .env
COPY .env /var/www/html/.env

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Installa Composer e le dipendenze PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Espone la porta (Render.com utilizza la variabile $PORT)
EXPOSE 10000

# Avvia Nginx e PHP-FPM
CMD ["sh", "-c", "envsubst '$PORT' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf && php-fpm -D && nginx -g 'daemon off;'"]
