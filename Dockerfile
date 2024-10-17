FROM php:8.2-fpm

# Inštalácia potrebných balíkov
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Inštalácia Composeru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nastavenie pracovného adresára
WORKDIR /var/www

# Skopírovanie všetkých súborov
COPY . .

# Inštalácia závislostí
RUN composer install




# Exponovanie portu
EXPOSE 9000
