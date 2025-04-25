FROM php:8.2-apache

# Installer extensions PHP nécessaires à WordPress
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev zip unzip libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd

# Copie les fichiers WordPress dans /var/www/html
COPY . /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html

# Active le module rewrite pour les permaliens WordPress
RUN a2enmod rewrite
