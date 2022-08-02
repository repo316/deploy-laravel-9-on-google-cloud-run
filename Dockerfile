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

RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && \
    /usr/local/bin/composer install --no-dev

RUN chown -R www-data: /app

RUN cd /app && \
    composer install

CMD sh /app/docker/startup.sh
