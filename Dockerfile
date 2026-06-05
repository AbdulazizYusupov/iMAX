FROM php:8.4-fpm-alpine

# Tizim paketlari, git, nginx va supervisor o'rnatish
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git

# Alpine tizimidagi standart PDO drayverlarini sozlash
RUN docker-php-ext-install bcmath

# Composer yuklash
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Bog'liqliklarni o'rnatish
RUN composer install --no-dev --optimize-autoloader

# SQLite bazasini tekshirish va yaratish
RUN mkdir -p database && touch database/database.sqlite

# Huquqlarni Nginx foydalanuvchisiga berish
RUN chown -R www-data:www-data storage bootstrap/cache database

# Render konfiguratsiyalarini nusxalash
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY .render/supervisord.conf /etc/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]