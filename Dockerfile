FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

ENV WEBROOT=/var/www/html/public

CMD ["/start.sh"]