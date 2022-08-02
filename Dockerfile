FROM php:8.1-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN apk add --no-cache libpng libpng-dev && docker-php-ext-install gd && apk del libpng-dev
RUN apk add --no-cache \
        libzip-dev \
        zip \
  && docker-php-ext-install zip


RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /app
COPY . /app
COPY ./src /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/app --filename=composer

RUN chown -R www-data: /app

CMD sh /app/docker/startup.sh
