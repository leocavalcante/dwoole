FROM php:7.3-cli

ADD docker-entrypoint.sh /usr/local/bin
ADD get-composer.sh /usr/local/bin
ADD watch.php /usr/local/bin

RUN apt-get update && apt-get install -y \
  curl \
  git \
  libfreetype6-dev \
  libhiredis-dev \
  libjpeg62-turbo-dev \
  libnghttp2-dev \
  libpcre3 \
  libpcre3-dev \
  libpng-dev \
  libssh-dev \
  openssl \
  procps \
  unzip \
  vim \
  wget \
  zip \
  && apt autoremove && apt clean

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install -j$(nproc) \
  exif \
  gd \
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
  --enable-async-redis \
  --enable-coroutine \
  --enable-http2 \
  --enable-mysqlnd \
  --enable-openssl \
  --enable-sockets \
  && make && make install \
  && docker-php-ext-enable swoole

WORKDIR /app
EXPOSE 9501

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
