FROM leocavalcante/dwoole:base

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
  && apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev zstd-dev postgresql-dev \
  && docker-php-ext-install -j$(nproc) exif gd mysqli pdo_mysql pdo_pgsql zip \
  && yes | pecl install igbinary redis mongodb xdebug \
  && docker-php-ext-enable igbinary redis mongodb xdebug \
  && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.remote_mode=req" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD docker-entrypoint.sh watch.php /usr/local/bin/
ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
