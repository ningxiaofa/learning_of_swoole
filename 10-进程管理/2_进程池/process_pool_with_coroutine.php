<?php

use Swoole\Coroutine;

$pool = new Swoole\Process\Pool(1, SWOOLE_IPC_NONE, 0, true);

$pool->on('workerStart', function (Swoole\Process\Pool $pool, int $workerId) {
    while (true) {
        Coroutine::sleep(0.5);
        echo "hello world\n";
    }
});

$pool->start();

// ➜  learning_of_swoole git:(main) ✗ php  6-协程高级/4_进程API/进程池/process_pool_with_coroutine.php    
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// hello world
// ^C
// ➜  learning_of_swoole git:(main) ✗ 