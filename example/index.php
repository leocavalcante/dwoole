<?php declare(strict_types=1);
require_once __DIR__.'/vendor/autoload.php';

use Siler\Swoole;

$server = function ($request, $response) {
    Swoole\emit('Hello World');
};

Swoole\http($server)->start();
