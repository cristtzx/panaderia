# Usa la imagen base de PHP con Apache
FROM php:8.2-apache

# Instala dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    zip \
    unzip \
    git \
    curl \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml pdo_pgsql \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*  # Limpieza de caché

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Ajusta los permisos de los archivos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Instala las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto 80
EXPOSE 80

# Copia la configuración de Apache
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Reinicia Apache para aplicar cambios
CMD ["apache2-foreground"]