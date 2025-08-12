# ---------- Etapa 1: PHP + Composer + Node para build ----------
FROM php:8.2-fpm-bullseye AS phpbuild

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev libpq-dev libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libssl-dev libcurl4-openssl-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql zip intl gd opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node (para Vite si aplica)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

WORKDIR /var/www/html
COPY . /var/www/html

RUN composer install --no-dev --prefer-dist --optimize-autoloader \
    && ( [ -f package.json ] && npm ci && npm run build || echo "No frontend" ) \
    && php artisan storage:link || true

# ---------- Etapa 2: Imagen final con Nginx + PHP-FPM + Supervisord ----------
FROM debian:bullseye

RUN apt-get update && apt-get install -y \
    nginx supervisor curl ca-certificates gettext-base \
    libzip4 libicu67 libxml2 libpng16-16 libjpeg62-turbo \
    && rm -rf /var/lib/apt/lists/*

# Copiamos PHP de la etapa build
COPY --from=phpbuild /usr/local /usr/local
COPY --from=phpbuild /usr/lib /usr/lib
COPY --from=phpbuild /etc/php /etc/php
COPY --from=phpbuild /var/www/html /var/www/html

# Archivos de deploy
COPY deploy/nginx.conf.template /etc/nginx/nginx.conf.template
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permisos Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render escucha en $PORT; exponemos uno por defecto
EXPOSE 10000

CMD ["/entrypoint.sh"]
