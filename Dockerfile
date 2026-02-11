# Dockerfile pour Laravel
FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Créer le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers Laravel
COPY . .

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# Donner les droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Exposer le port (PHP-FPM)
EXPOSE 9000

CMD ["php-fpm"]
