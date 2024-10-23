# Usa un'immagine PHP con estensioni per PostgreSQL
FROM php:8.0-fpm

# Installa estensione PDO_pgsql
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copia i file del progetto nella cartella /var/www/html
COPY . /var/www/html

# Imposta la directory di lavoro
WORKDIR /var/www/html

# Espone la porta 80
EXPOSE 80

# Avvia il server PHP
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]
