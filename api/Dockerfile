# Usa una base di PHP-FPM
FROM php:8.1-fpm as base

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

# Fase di Build Composer
FROM base as build

# Copia Composer nel sistema
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia solo composer.json e composer.lock per ottimizzare la cache di build
COPY composer.json composer.lock /var/www/html/

# Installa le dipendenze di Composer
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Fase di produzione
FROM base

# Copia la configurazione Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.template

# Copia il file .env
COPY .env /var/www/html/.env

# Copia i file della tua applicazione dalla fase di build
COPY --from=build /var/www/html /var/www/html

# Copia tutto il resto (codice applicativo)
COPY . /var/www/html

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Espone la porta (Render.com utilizza la variabile $PORT)
EXPOSE 10000

# Avvia Nginx e PHP-FPM
CMD ["sh", "-c", "envsubst '$PORT' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf && php-fpm -D && nginx -g 'daemon off;'"]
