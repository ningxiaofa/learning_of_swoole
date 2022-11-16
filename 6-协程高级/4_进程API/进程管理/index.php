<?php

use Swoole\Process\Manager;
use Swoole\Process\Pool;

$pm = new Manager();

for ($i = 0; $i < 2; $i++) {
    $pm->add(function (Pool $pool, int $workerId) {
        echo "workid: $workerId \n";
    });
}

$pm->start();

// learning_of_swoole git:(main) ✗ php /Users/huangbaoyin/Documents/Code/swoole/learning_of_swoole/6-协程高级/4_进程API/进程管理/index.php
// workid: 0 
// workid: 1 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
// workid: 1 
// workid: 0 
