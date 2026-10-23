# PHP 8.2 em Alpine (imagem bem menor que php:apache) com o servidor web embutido do PHP.
# A tag 8.2-cli-alpine acompanha as correções de segurança do 8.2: reconstrua com "docker compose up -d --build" para pegar a mais nova.
# Já vêm no PHP: SQLite (pdo_sqlite e sqlite3). Aqui instalamos: MySQL (mysqli, pdo_mysql) e PostgreSQL (pdo_pgsql).
FROM php:8.2-cli-alpine

# libpq e sqlite ficam (biblioteca do PostgreSQL e comando sqlite3); as ferramentas de compilação saem no final
RUN apk add --no-cache libpq sqlite \
    && apk add --no-cache --virtual .build-deps postgresql-dev \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql pdo_pgsql \
    && apk del .build-deps

WORKDIR /var/www/html
EXPOSE 80
