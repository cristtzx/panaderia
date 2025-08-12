# ---------- Etapa 1: build ----------
FROM php:8.2-fpm-bullseye AS phpbuild
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libxml2-dev libpq-dev libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libssl-dev libcurl4-openssl-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql zip intl gd opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
    && ( [ -f package.json ] && npm ci && npm run build || echo "No frontend" ) \
    && php artisan storage:link || true

# ---------- Etapa 2: runtime (usa php 8.2 fpm) ----------
FROM php:8.2-fpm-bullseye

RUN apt-get update && apt-get install -y \
    nginx supervisor curl ca-certificates gettext-base \
    libzip4 libicu67 libxml2 libpng16-16 libjpeg62-turbo \
    && rm -rf /var/lib/apt/lists/*

# Trae binarios/extensiones construidos en la etapa build (opcional pero útil)
COPY --from=phpbuild /usr/local /usr/local

# ✅ Instala los drivers por si acaso (garantiza pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# App y configs
COPY --from=phpbuild /var/www/html /var/www/html
COPY deploy/nginx.conf.template /etc/nginx/nginx.conf.template
COPY deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY deploy/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000
CMD ["/entrypoint.sh"]
