#!/bin/sh
set -e

if [ -z "$ENTRY_POINT_FILE" ]
then
  php /app/index.php
else
  php "$ENTRY_POINT_FILE"
fi
