FROM php:8.2-apache
WORKDIR /var/www/html/
COPY data/ .
COPY index.php lib.php ./
EXPOSE 80