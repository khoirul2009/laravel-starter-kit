# =========================
# Composer dependencies
# =========================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .

RUN composer dump-autoload --optimize


# =========================
# Node build for Inertia/Vite
# =========================
FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build


# =========================
# Runtime FrankenPHP
# =========================
FROM dunglas/frankenphp

WORKDIR /app

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    opcache \
    intl \
    zip \
    bcmath

COPY . /app

COPY --from=vendor /app/vendor /app/vendor
COPY --from=frontend /app/public/build /app/public/build

# RUN php artisan config:cache \
#     && php artisan route:cache \
#     && php artisan view:cache

ENTRYPOINT ["php", "artisan", "octane:start --watch"]