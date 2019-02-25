FROM php:7.3-cli

ADD docker-entrypoint.sh /usr/local/bin
ADD get-composer.sh /usr/local/bin
ADD watch.php /usr/local/bin

RUN apt-get update && apt-get install -y git unzip wget \
  && docker-php-ext-install -j$(nproc) pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN pecl install swoole mongodb \
  && echo "extension=swoole.so" >> "$PHP_INI_DIR/php.ini" \
  && echo "extension=mongodb.so" >> "$PHP_INI_DIR/php.ini"

WORKDIR /app
EXPOSE 9501

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
