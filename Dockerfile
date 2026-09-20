FROM php:8.3-fpm-alpine

# Instala Nginx, Supervisor, Node.js e dependências nativas
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    nodejs \
    npm \
    linux-headers \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala o Composer a partir da imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia todo o código da aplicação
COPY . .

# Copia as configurações do Nginx e do Supervisor
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Define as permissões corretas para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]