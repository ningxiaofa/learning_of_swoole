<?php

// exec - 与exec进程进行管道通信
use Swoole\Process;
use function Swoole\Coroutine\run;

// 子进程
$process = new Process(function (Process $worker) {
    $worker->exec('/bin/echo', ['hello']);
}, true, 1, true); // 需要启用标准输入输出重定向

$process->start();

// 父进程「因为子进程开启了协程，父进程也需要运行在协程容器中」
run(function () use ($process) {
    $socket = $process->exportSocket();
    echo "from exec: " . $socket->recv() . "\n";
});


// 执行结果:
// ➜  learning_of_swoole git:(main) ✗ php 6-协程高级/4_进程API/程序调用/exec_with_exec.php
// from exec: hello

// ➜  learning_of_swoole git:(main) ✗ 