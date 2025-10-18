FROM php:8.2-fpm

# dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath opcache zip gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Permissões
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/backend