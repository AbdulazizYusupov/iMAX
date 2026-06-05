FROM php:8.4-fpm-alpine

# Tizim paketlari va Git o'rnatish
RUN apk add --no-cache nginx supervisor curl libpng-dev libxml2-dev zip unzip git

# PHP kengaytmalarini o'rnatish
RUN docker-php-ext-install bcmath

# Composer yuklash
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Bog'liqliklarni o'rnatish
RUN composer install --no-dev --optimize-autoloader

# Render konfiguratsiyalarini nusxalash
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY .render/supervisord.conf /etc/supervisord.conf

# Papkalarni ochish va huquq berish
RUN mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80

# Konteyner yonganda migratsiya ishlaydi va server start oladi
CMD php artisan migrate --force && /usr/bin/supervisord -c /etc/supervisord.conf