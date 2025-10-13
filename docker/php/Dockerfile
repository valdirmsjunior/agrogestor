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
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Permissões
RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/backend