<?php 

use Swoole\Http\Server;

$server = new Server('0.0.0.0', 8080);

$server->on('request', function ($request, $response) {
    $response->header('Content-Type', 'text/plain; charset=utf-8');
    $response->end('Recebi a requisição!');
});

$server->start();


// docker run -itv $(pwd):/app -w /app -p 8080:8080 php-swoole bash
