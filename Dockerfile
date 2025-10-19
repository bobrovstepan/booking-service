FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql curl calendar zip

COPY . /var/www/html

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
 && sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

EXPOSE 80
