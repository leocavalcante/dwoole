FROM php:7.3-cli-stretch

RUN apt-get update && apt-get install -y \
  curl \
  git \
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
ADD install_composer.sh /usr/local/bin/install_composer.sh
RUN ./usr/local/bin/install_composer.sh

RUN git clone https://github.com/swoole/swoole-src.git \
  && cd swoole-src \
  && git checkout v4.4.3 \
  && phpize \
  && ./configure \
  --enable-openssl \
  --enable-sockets \
  --enable-http2 \
  --enable-mysqlnd \
  && make && make install \
  && docker-php-ext-enable swoole \
  && rm -rf /tmp/*

WORKDIR /app
EXPOSE 9501
