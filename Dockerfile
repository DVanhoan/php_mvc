
FROM php:8.3.9-apache


RUN docker-php-ext-install mysqli pdo pdo_mysql


COPY ./src /var/www/html