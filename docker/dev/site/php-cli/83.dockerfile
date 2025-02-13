FROM php:8.3-cli-alpine

ARG UUID=1000
ARG UGID=1000

ENV UUID $UUID
ENV UGID $UGID

RUN apk add --no-cache mariadb-dev bash git openssh-client linux-headers \
    && docker-php-ext-install pdo_mysql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis.so

RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl \
    && docker-php-ext-configure intl

COPY ./php/conf.d /usr/local/etc/php/conf.d
COPY ./wait-for-it.sh /usr/local/bin/wait-for-it

RUN chmod 555 /usr/local/bin/wait-for-it

RUN addgroup -S $UGID && adduser -u $UUID -G $UGID -S app -s /bin/bash -D app

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

USER app
