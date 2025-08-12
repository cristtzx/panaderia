#!/usr/bin/env bash
set -e

# Render provee $PORT; por defecto 10000
export PORT="${PORT:-10000}"

# Rellena nginx.conf con el PORT real
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Pequeña optimización de Laravel si existe artisan
if [ -f /var/www/html/artisan ]; then
  cd /var/www/html
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

exec /usr/bin/supervisord -n
