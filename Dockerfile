FROM php:7.4-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql
COPY . /var/www/html/pos
RUN mv /var/www/html/pos/config/db-docker.php /var/www/html/pos/config/db.php
WORKDIR /var/www/html/pos
