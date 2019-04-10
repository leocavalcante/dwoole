#!/bin/sh
set -e

sh /usr/local/bin/get-composer.sh
php composer.phar install

if [ -z "$ENTRY_POINT_FILE" ]
then
  php /app/index.php
else
  php $ENTRY_POINT_FILE
fi
