FROM php:7.3-cli

RUN apt-get update && apt-get install -y \
  curl \
  git \
  libhiredis-dev \
  libnghttp2-dev \
  libpcre3 \
  libpcre3-dev \
  libssh-dev \
  openssl \
  procps \
  unzip \
  && apt autoremove && apt clean

RUN docker-php-ext-install -j$(nproc) \
  iconv \
  json \
  mbstring  \
  opcache \
  pcntl \
  sockets \
  && echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

RUN git clone https://github.com/swoole/swoole-src.git \
  && cd swoole-src \
  && phpize \
  && ./configure \
  --enable-async-redis \
  --enable-coroutine \
  --enable-http2 \
  --enable-mysqlnd \
  --enable-openssl \
  --enable-sockets \
  && make && make install \
  && docker-php-ext-enable swoole \
  && rm -rf /tmp/*

WORKDIR /app
EXPOSE 9501
