FROM php:8.2-apache

# Instalamos el driver de MySQL para que PHP pueda conectarse a la base de datos
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/html/
