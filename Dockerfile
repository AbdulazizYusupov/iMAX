FROM php:8.4-fpm-alpine

# Tizim paketlari va SQLite-ni o'rnatish
RUN apk add --no-cache nginx supervisor curl libpng-dev libxml2-dev zip unzip

# PHP kengaytmalarini o'rnatish
RUN docker-php-ext-install bcmath pdo_sqlite

# Composer-ni yuklab olish
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Production sozlamalari
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data storage bootstrap/cache database

# Nginx va Supervisor konfiguratsiyasi
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY .render/supervisord.conf /etc/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]