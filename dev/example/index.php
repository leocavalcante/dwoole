<?php declare(strict_types=1);

use function Siler\Swoole\{http, emit};

$dir = __DIR__;
require_once "$dir/vendor/autoload.php";

$handler = static function () {
    var_dump('Hello, World!');
    emit('Hello, Word!');
};


http($handler)->start();
