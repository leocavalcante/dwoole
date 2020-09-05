FROM leocavalcante/dwoole:base
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ADD docker-entrypoint.sh /usr/local/bin
ENTRYPOINT [ "sh", "/usr/local/bin/docker-entrypoint.sh" ]
