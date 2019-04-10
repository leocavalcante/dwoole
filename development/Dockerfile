FROM leocavalcante/dwoole-base:1.0

ADD docker-entrypoint.sh /usr/local/bin
ADD watch.php /usr/local/bin

RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  && apt autoremove && apt clean

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install -j$(nproc) \
  exif \
  gd \
  mysqli \
  pdo_mysql

RUN pecl install mongodb \
  && docker-php-ext-enable mongodb

RUN pecl install redis \
  && docker-php-ext-enable redis

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
