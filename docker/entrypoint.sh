#!/bin/bash
set -e

echo "=== Laravel Container Starting ==="

# Attendre que la base de données soit prête
echo "Waiting for database..."
until pg_isready -h db -U realuser -d realestate; do
    echo "Database is unavailable - sleeping"
    sleep 2
done
echo "Database is ready!"

# CORRECTION DES PERMISSIONS (CRITIQUE)
echo "Setting up permissions..."

# Supprimer l'ancien fichier de vue cache problématique
rm -f /var/www/html/storage/framework/views/*.php

# Créer les dossiers s'ils n'existent pas
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Permissions complètes pour storage et bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Changer le propriétaire
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "Permissions set successfully!"

# Installer les dépendances composer si nécessaire
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Générer la clé d'application si nécessaire
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:placeholder" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Nettoyer TOUS les caches
echo "Clearing all caches..."
php artisan config:clear

php artisan view:clear
php artisan route:clear

# Exécuter les migrations
echo "Running migrations..."
php artisan migrate --force
php artisan cache:clear
# Créer le lien symbolique pour storage
echo "Creating storage link..."
php artisan storage:link --force

# Optimiser pour production
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache

# Vérifier les permissions une dernière fois
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "=== Laravel is ready! ==="

# Démarrer PHP-FPM
exec php-fpm