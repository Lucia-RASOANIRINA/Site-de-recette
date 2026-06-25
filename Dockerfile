# =====================================================================
#  Image de déploiement OURATABLE (Laravel 9 / PHP 8.2)
#  Compatible Render.com, Railway, Fly.io et tout hébergeur Docker.
# =====================================================================
FROM php:8.2-cli

# --- Dépendances système et extensions PHP ---
RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev libonig-dev libicu-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring zip gd intl bcmath exif \
    && rm -rf /var/lib/apt/lists/*

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

# --- Installation des dépendances PHP (sans dev) ---
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p storage/app/public/recettes storage/app/public/posts storage/app/public/avatars \
       storage/framework/cache storage/framework/sessions storage/framework/views \
    && touch database/database.sqlite \
    && php artisan storage:link || true

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/app/database/database.sqlite

EXPOSE 8000

# Au démarrage : migrations + données de démo, puis serveur HTTP.
CMD php artisan migrate --force --seed \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
