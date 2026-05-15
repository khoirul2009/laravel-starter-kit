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

RUN composer require \
    open-telemetry/sdk \
    open-telemetry/opentelemetry-auto-laravel \
    open-telemetry/exporter-otlp \
    open-telemetry/transport-http \
    php-http/guzzle7-adapter \
    --no-interaction \
    --no-scripts \
    --update-no-dev \
    --ignore-platform-req=ext-opentelemetry \
    --ignore-platform-req=ext-protobuf

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
    bcmath \
    opentelemetry

COPY . /app

COPY --from=vendor /app/vendor /app/vendor
COPY --from=frontend /app/public/build /app/public/build

RUN mkdir -p \
    /app/storage/framework/cache/data \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/framework/testing \
    /app/storage/app/public \
    /app/storage/app/private \
    /app/storage/logs \
    /app/bootstrap/cache \
    && chmod -R ug+rwX /app/storage /app/bootstrap/cache

# RUN php artisan config:cache \
#     && php artisan route:cache \
#     && php artisan view:cache

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]