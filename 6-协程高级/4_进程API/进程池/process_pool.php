<?php

use Swoole\Process;
use Swoole\Coroutine;

$pool = new Process\Pool(5);
$pool->set(['enable_coroutine' => true]);
$pool->on('WorkerStart', function (Process\Pool $pool, $workerId) {
    /** 当前是 Worker 进程 */
    static $running = true;
    Process::signal(SIGTERM, function () use (&$running) {
        $running = false;
        echo "TERM\n";
    });
    echo("[Worker #{$workerId}] WorkerStart, pid: " . posix_getpid() . "\n");
    while ($running) {
        Coroutine::sleep(1);
        echo "sleep 1\n";
    }
});
$pool->on('WorkerStop', function (\Swoole\Process\Pool $pool, $workerId) {
    echo("[Worker #{$workerId}] WorkerStop\n");
});
$pool->start();


// 执行结果:
// ➜  learning_of_swoole git:(main) ✗ php 6-协 程高级/4_进程API/进程池/process_pool.php
// [Worker #0] WorkerStart, pid: 22697
// [Worker #1] WorkerStart, pid: 22698
// [Worker #2] WorkerStart, pid: 22699
// [Worker #3] WorkerStart, pid: 22700
// [Worker #4] WorkerStart, pid: 22701
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// sleep 1
// ^C
// ➜  learning_of_swoole git:(main) ✗ 