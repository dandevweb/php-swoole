<?php 

use Swoole\Coroutine\Http\Client;
use Swoole\Http\{Server, Request, Response};

$server = new Server('0.0.0.0', 8080);

$server->on('request', function (Request $request, Response $response) {
    $channel = new chan(2);
    
    go(function() use ($channel){
        $client = new Client('localhost', 8001);
        $client->get('/server.php');

        $content = $client->getBody();
        $channel->push($content);
    });

    go(function() use ($channel){
        $content = file_get_contents('arquivo.txt');
        $channel->push($content);
    });

    go(function () use (&$response, $channel) {
        $firstResponse = $channel->pop();
        $secondResponse = $channel->pop();

        $response->end($firstResponse . $secondResponse);
        
    });
});

$server->start();


// docker run -itv $(pwd):/app -w /app -p 8080:8080 php-swoole bash
