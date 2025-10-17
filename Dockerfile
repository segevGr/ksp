FROM php:8.2-apache
WORKDIR /var/www/html/
COPY /app /var/www/html/
HEALTHCHECK \
	CMD curl localhost:80 | grep "active_count"
EXPOSE 8081