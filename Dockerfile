FROM php:8.2-apache

# Instalar dependências do sistema para PostgreSQL e SQLite
RUN apt-get update && apt-get install -y \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP para MySQL, PostgreSQL e SQLite
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pdo_sqlite

# Garantir permissões na pasta padrão do servidor Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html
