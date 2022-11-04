<?php

// https://wiki.swoole.com/#/server/methods?id=addprocess

$server = new Swoole\Server('127.0.0.1', 9501);

/**
 * 用户进程实现了广播功能，循环接收unixSocket的消息，并发给服务器的所有连接
 * [循环接收unixSocket的消息? 暂时不明白]
 */
$process = new Swoole\Process(function ($process) use ($server) {
    $socket = $process->exportSocket();
    while (true) {
        $msg = $socket->recv();
        foreach ($server->connections as $conn) {
            $server->send($conn, $msg);
        }
    }
}, false, 2, 1);

$server->addProcess($process);

$server->on('receive', function ($serv, $fd, $reactor_id, $data) use ($process) {
    //群发收到的消息
    $socket = $process->exportSocket();
    $socket->send($data);
});

$server->start();

echo 123; // 不会执行{什么时候会执行?TBD}
