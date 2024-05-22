<?php 

use Swoole\Coroutine\Http\Client;
use Swoole\Http\{Server, Request, Response};

Co::set(['hook_flags' => SWOOLE_HOOK_ALL]);

$server = new Server('0.0.0.0', 8080);

$server->on('request', function (Request $request, Response $response) {
    $channel = new chan(2);
    
    go(function() use ($channel){
        $curl = curl_init('http://localhost:8001/server.php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($curl);
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
