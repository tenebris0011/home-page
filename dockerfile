FROM php:8.0-apache
RUN apt-get update 

RUN apt-get install sudo
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod ssl && a2enmod rewrite

RUN rm /etc/apache2/sites-available/000-default.conf
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
EXPOSE 443