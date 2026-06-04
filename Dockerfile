# =========================
# 1. Node build for Inertia/Vite
# =========================
FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY . .

RUN npm run build

# =========================
# 2. Runtime & Dependency Build (FrankenPHP PHP 8.4)
# =========================
FROM dunglas/frankenphp:php8.4 AS runtime

WORKDIR /app

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    pdo_sqlite \
    opcache \
    intl \
    zip \
    bcmath \
    sockets \
    opentelemetry

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

RUN composer require \
    open-telemetry/sdk \
    open-telemetry/exporter-otlp \
    open-telemetry/opentelemetry-auto-laravel \
    open-telemetry/opentelemetry-auto-psr18 \
    open-telemetry/opentelemetry-auto-slim \
    monolog/monolog \
    open-telemetry/opentelemetry-logger-monolog \
    php-http/guzzle7-adapter \
    --no-interaction \
    --no-scripts \
    --update-no-dev

COPY . /app
COPY --from=frontend /app/public/build /app/public/build

# Optimation Autoload
RUN composer dump-autoload --optimize

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


ENTRYPOINT ["php", "artisan", "octane:frankenphp"]