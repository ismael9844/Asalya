# Stage 1: Build des assets
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build

# Stage 2: Image finale PHP
FROM php:8.2-fpm

# Installer les dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    postgresql-client \
    gcc \
    g++ \
    make \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        intl \
        zip \
        gd \
        bcmath \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier tous les fichiers du projet
COPY . .

# Copier les assets buildés depuis le stage Node
COPY --from=node-builder /app/public/build ./public/build

# Supprimer le fichier hot si présent
RUN rm -f public/hot

# Installer les dépendances PHP
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Créer les dossiers nécessaires
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache

# Permissions initiales
RUN chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Copier le script d'entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

# Utiliser l'entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]