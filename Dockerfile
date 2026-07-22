FROM php:8.4-fpm-alpine AS php

RUN docker-php-ext-install pdo_mysql

RUN printf "post_max_size = 5M\nupload_max_filesize = 5M\n" >> ${PHP_INI_DIR}/php.ini

RUN install -o www-data -g www-data -d /var/www/upload/image/
