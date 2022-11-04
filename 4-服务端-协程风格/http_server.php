<?php

// https://wiki.swoole.com/#/coroutine/http_server

use Swoole\Coroutine\Http\Server;
use function Swoole\Coroutine\run;


run(function () {
    $server = new Server('127.0.0.1', 9502, false);
    // 设置 / 根路径处理函数[比较特殊，也是默认uri]
    $server->handle('/', function ($request, $response) {
        $response->end("<h1>Index</h1>");
    });

    // 前缀匹配算法: /test**** 是一定会执行 /test
    // /test12没有机会执行，所以，要基于swoole进行项目开发，需要在回调函数中使用 $request->server['request_uri'] 进行请求路由
    $server->handle('/test', function ($request, $response) {
        $response->end("<h1>Test</h1>");
    });
    // [不会执行]
    $server->handle('/test12', function ($request, $response) {
        $response->end("<h1>Test 12</h1>");
    });


    $server->handle('/stop', function ($request, $response) use ($server) {
        $response->end("<h1>Stop</h1>");
        $server->shutdown();
    });
    $server->start();
});
