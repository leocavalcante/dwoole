FROM php:7.3-cli

ADD docker-entrypoint.sh /usr/local/bin
ADD get-composer.sh /usr/local/bin
ADD watch.php /usr/local/bin

RUN apt-get update && apt-get install -y \
  curl \
  git \
  libhiredis-dev \
  libnghttp2-dev \
  libpcre3 \
  libpcre3-dev \
  libssh-dev \
  openssl \
  unzip \
  vim \
  wget \
  zip \
  && apt autoremove && apt clean

RUN docker-php-ext-install -j$(nproc) \
  iconv \
  json \
  mbstring  \
  opcache \
  pdo_mysql \
  sockets && \
  echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN pecl install mongodb \
  && docker-php-ext-enable mongodb

RUN pecl install redis \
  && docker-php-ext-enable redis

RUN git clone https://github.com/swoole/swoole-src.git \
  && cd swoole-src \
  && phpize \
  && ./configure \
  --enable-coroutine \
  --enable-openssl \
  --enable-http2 \
  --enable-async-redis \
  --enable-sockets \
  --enable-mysqlnd \
  && make && make install \
  && docker-php-ext-enable swoole

WORKDIR /app
EXPOSE 9501

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
