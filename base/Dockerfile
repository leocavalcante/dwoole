FROM php:7.4-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apk add --no-cache $PHPIZE_DEPS git openssl-dev oniguruma-dev \
  && docker-php-ext-install -j$(nproc) \
    iconv \
    json \
    mbstring  \
    opcache \
    pcntl \
    sockets \
  && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
  && git clone -b v4.5.6 https://github.com/swoole/swoole-src.git \
  && cd swoole-src \
  && phpize \
  && ./configure \
  --enable-sockets \
  --enable-openssl \
  --enable-http2 \
  && make && make install \
  && docker-php-ext-enable swoole

WORKDIR /app
