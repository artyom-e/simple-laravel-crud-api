FROM php:8.4

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && apt-get clean \
    && apt-get autoremove \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_pgsql
