FROM leocavalcante/dwoole-base:1.0

ADD docker-entrypoint.sh /usr/local/bin

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
