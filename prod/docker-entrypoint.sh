#!/bin/sh
set -e

composer install --prefer-dist --no-dev --optimize-autoloader

echo "Start"
if [ -z "$ENTRY_POINT_FILE" ]
then
  php /app/index.php
else
  php "$ENTRY_POINT_FILE"
fi
