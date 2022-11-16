<?php

$pool = new Swoole\Process\Pool(1, SWOOLE_IPC_MSGQUEUE, 0, true);

$pool->on('workerStart', function (Swoole\Process\Pool $pool, int $workerId) {
    $process = $pool->getProcess(0);
    $socket = $process->exportSocket();
    // var_dump($workerId) . PHP_EOL;
    if ($workerId == 0) {
        echo $socket->recv();
        $socket->send("hello proc1\n");
        echo "proc0 stop\n";
    } else {
        echo '123' . PHP_EOL;
        $socket->send("hello proc0\n");
        echo $socket->recv();
        echo "proc1 stop\n";
        $pool->shutdown();
    }
});

$pool->start();

// ➜  learning_of_swoole git:(main) ✗ php  6-协程高级/4_进程API/进程池/ipc_unixsocket.php
// 123
// hello proc0
// proc0 stop
// hello proc1
// proc1 stop
// ➜  learning_of_swoole git:(main) ✗ 
