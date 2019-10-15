FROM php:alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apk add --no-cache $PHPIZE_DEPS git openssl-dev \
  && docker-php-ext-install -j$(nproc) \
    iconv \
    json \
    mbstring  \
    opcache \
    pcntl \
    sockets \
  && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \
  && git clone https://github.com/swoole/swoole-src.git \
  && cd swoole-src \
  && git checkout v4.4.8 \
  && phpize \
  && ./configure \
  --enable-openssl \
  --enable-sockets \
  --enable-http2 \
  --enable-mysqlnd \
  && make && make install \
  && docker-php-ext-enable swoole

WORKDIR /app
