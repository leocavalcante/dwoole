#!/bin/sh
set -e

composer install

echo "Watch"
php /usr/local/bin/watch.php
