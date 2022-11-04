<?php

// https://wiki.swoole.com/#/coroutine/server

// Swoole\Coroutine\Server 是一个完全协程化的类，用于创建协程 TCP 服务器，支持 TCP 和 unixSocket 类型。

// Note:
// 下面的代码，使用客户端：telnet localhost 9501
// 需要很快发送请求数据，不然很快就会服务端超时，然后连接被关闭:[原因就在handle方法中]
// 服务端输出信息：errCode: 60, errMsg: Operation timed out
// 但是服务端依然正常接收另外的tcp请求
// 客户端报错：连接被外部主机关闭。

use Swoole\Process;
use Swoole\Coroutine;
use Swoole\Coroutine\Server\Connection;

//多进程管理模块
$pool = new Process\Pool(2);
//让每个OnWorkerStart回调都自动创建一个协程
$pool->set(['enable_coroutine' => true]);
$pool->on('workerStart', function ($pool, $id) {
    //每个进程都监听9501端口
    $server = new Swoole\Coroutine\Server('127.0.0.1', 9501, false, true);

    //收到15信号关闭服务
    Process::signal(SIGTERM, function () use ($server) {
        $server->shutdown();
    });

    //接收到新的连接请求 并自动创建一个协程
    $server->handle(function (Connection $conn) {
        while (true) {
            //接收数据
            $data = $conn->recv(1);

            // 注意，这里第一次，一定会先于Coroutine::sleep(1);执行，所以连接上之后，快速发送请求，不然几乎立马就会断开.
            if ($data === '' || $data === false) {
                $errCode = swoole_last_error();
                $errMsg = socket_strerror($errCode);
                echo "errCode: {$errCode}, errMsg: {$errMsg}\n";
                $conn->close();
                break;
            }
            echo $data;

            //发送数据
            $conn->send('hello');

            Coroutine::sleep(1);
        }
    });

    //开始监听端口
    $server->start();
});
$pool->start();

