#!/usr/bin/env bash
export PHP_BIN=/usr/bin/php
export WATCH_DIR=$(pwd)/example
export ENTRY_POINT_FILE=$(pwd)/example/index.php
export DEBUG=true
export WATCH_INTERVAL=500
php watch.php
