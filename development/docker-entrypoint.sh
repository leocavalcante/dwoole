#!/bin/sh
set -e

sh /usr/local/bin/get-composer.sh
php composer install
php /usr/local/bin/watch.php