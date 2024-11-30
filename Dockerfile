
FROM wyveo/nginx-php-fpm:php82



WORKDIR /backend


#USER www-data
COPY . .
# # COPY . .

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
RUN composer install


 # COPY . .


RUN chmod -R 777 storage/
RUN chmod -R 777 bootstrap/
# RUN php artisan key:generate
# RUN php artisan migrate
# # expose 900060gi
# # CMD ["php-fpm"]
# # FROM nginx

 COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf 