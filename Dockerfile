# FROM alpine:latest
# LABEL Name=apiaem Version=0.0.1
# RUN apk add --no-cache fortune
# ENTRYPOINT ["sh", "-c", "fortune -a | cat"]
FROM php:8.3-cli

# Dependencias del sistema
RUN apt-get update && apt-get upgrade && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install \
    --no-interaction \
    --prefer-dist

COPY . .

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Puerto de Laravel
EXPOSE 8000

# Iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
