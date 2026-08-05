#!/bin/bash
set -e

echo "=== Laravel (Render) container starting ==="

: "${PORT:=10000}"
export PORT

# Générer la conf nginx avec le bon port fourni par Render
envsubst '${PORT}' < /etc/nginx/templates/render.conf.template > /etc/nginx/conf.d/default.conf

# Attendre la base de données si les variables sont fournies
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at $DB_HOST:${DB_PORT:-5432}..."
    for i in $(seq 1 30); do
        if pg_isready -h "$DB_HOST" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}" >/dev/null 2>&1; then
            echo "Database is ready!"
            break
        fi
        echo "Database is unavailable - sleeping ($i/30)"
        sleep 2
    done
fi

echo "Setting up permissions..."
mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data storage/logs bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating one now (set it in Render's dashboard to persist it across deploys)."
    php artisan key:generate --force
fi

echo "Clearing cached config from the build..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Running migrations..."
php artisan migrate --force

echo "Linking storage..."
php artisan storage:link --force || true

echo "Caching config for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Laravel is ready, starting nginx + php-fpm ==="
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
