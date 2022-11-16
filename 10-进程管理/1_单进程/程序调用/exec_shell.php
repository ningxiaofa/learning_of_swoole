<?php

// exec - 与exec进程进行管道通信
use Swoole\Process;
use function Swoole\Coroutine\run;

$process = new Process(function (Process $worker) {
    $worker->exec('/bin/sh', array('-c', "cp -rf /Users/huangbaoyin/Documents/Code/swoole/learning_of_swoole/6-协程高级/4_进程API/程序调用/data/test/* /Users/huangbaoyin/Documents/Code/swoole/learning_of_swoole/6-协程高级/4_进程API/程序调用/tmp/test/"));
});

$process->start();

// 执行结果:
// ➜  learning_of_swoole git:(main) ✗ php 6-协程高级/4_进程API/程序调用/exec_shell.php
// ➜  learning_of_swoole git:(main) ✗ 

