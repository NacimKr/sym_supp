FROM php:8.2

COPY . /var/app/html

WORKDIR /var/app/html

CMD ["php", "-S", "127.0.0.1:8080", "-t", "public"]