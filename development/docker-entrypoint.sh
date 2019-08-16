#!/bin/sh
set -e

composer install

echo "Night gathers, and now my watch begins."
php /usr/local/bin/watch.php
