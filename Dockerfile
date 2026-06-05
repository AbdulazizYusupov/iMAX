# ... (Tepa qismi o'zgarishsiz qoladi)

# Huquqlarni berish
RUN chmod -R 777 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

# 🔥 Konteyner qurilayotganda migratsiyani majburiy yurgizish va keshni tozalash
RUN php artisan migrate --force \
    && php artisan config:clear \
    && php artisan route:clear

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]