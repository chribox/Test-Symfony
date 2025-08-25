FROM php:8.3-fpm

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev curl git \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql zip
RUN docker-php-ext-install pdo pdo_sqlite    

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/symfony
