<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use function Siler\Swoole\{http, emit};

echo 'here';

$server = function () {
    $message = 'It works';
    var_dump($message);
    emit($message);
};

http($server)->start();
