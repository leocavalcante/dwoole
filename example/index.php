<?php declare(strict_types=1);

use Swoole\Http\Server;

$server = new Server('0.0.0.0', 9501);

$server->on('start', function () {
    echo "Swoole HTTP server is started at 0.0.0.0:9501\n";
});

$server->on('request', function ($request, $response) {
    $response->end('It works');
});

$server->start();
