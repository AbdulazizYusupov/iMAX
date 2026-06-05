# ... (Tepa qismi o'zgarishsiz qoladi)

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Render konfiguratsiyalarini nusxalash
COPY .render/nginx.conf /etc/nginx/http.d/default.conf
COPY .render/supervisord.conf /etc/supervisord.conf

# Papkalarni ochish va huquq berish (Build vaqtida faqat ruxsatlar sozlanadi)
RUN mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80

# 🔥 Eng muhim qator: Konteyner yongandagina (runtime) migratsiya ishlaydi
CMD php artisan migrate --force && /usr/bin/supervisord -c /etc/supervisord.conf