<?php

$workerNum = 10;
$pool = new Swoole\Process\Pool($workerNum);

$pool->on("WorkerStart", function ($pool, $workerId) {
    echo "Worker#{$workerId} is started\n";
    $redis = new Redis();
    $redis->pconnect('127.0.0.1', 63790);
    $key = "key1";
    while (true) {
        $msg = $redis->brpop($key, 2);
        if ($msg == null) continue;
        var_dump($msg);
    }
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n";
});

$pool->start();

// Local-Docker-Redis-Lnmp:0>RPUSH key1 a b c
// "3"
// Local-Docker-Redis-Lnmp:0>RPUSH key1 a b c
// "3"
// Local-Docker-Redis-Lnmp:0>RPUSH key1 a b c
// "3"
// Local-Docker-Redis-Lnmp:0> 

// learning_of_swoole git:(main) ✗ php 6-协程高级/4_进程API/进程池/start.php
// Worker#0 is started
// Worker#1 is started
// Worker#2 is started
// Worker#3 is started
// Worker#4 is started
// Worker#5 is started
// Worker#6 is started
// Worker#7 is started
// Worker#8 is started
// Worker#9 is started
// array(2) {
// array(2) {
//   [0]=>
//   [0]=>
// array(2) {
//     string(4) "string(4) "key1key1"
// "
//   [1]=>
//   [0]=>
//   [1]=>
//   string(1) "  cstring(4) ""
//   key1}
// "
//   [1]=>
//   string(1) "string(1) "ab"
// "
// }
// }
// array(2) {
// array(2) {
//   [0]=>
//   [0]=>
//     string(4) "string(4) "key1key1"
// "
//   [1]=>
//   [1]=>
//     string(1) "string(1) "ab"
// "
// }
// }
// array(2) {
//   [0]=>
//   string(4) "key1"
//   [1]=>
//   string(1) "c"
// }
// array(2) {
// array(2) {
// array(2) {
//   [0]=>
//   [0]=>
//     string(4) "string(4) "key1"
// key1  [1]=>
// "
//     [1]=>
// string(1) "b  [0]=>
// "
// }
//     string(4) "string(1) "key1c"
// "
//   [1]=>
// }
//   string(1) "a"
// }
// ^C
// ➜  learning_of_swoole git:(main) ✗ 
