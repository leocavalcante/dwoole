<?php declare(strict_types=1);

use function Siler\Swoole\{http, emit};

$dir = __DIR__;
require_once "$dir/vendor/autoload.php";

$handler = function () {
    emit('Hello World');
};

http($handler)->start();
