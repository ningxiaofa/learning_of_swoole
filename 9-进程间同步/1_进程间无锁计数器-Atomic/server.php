<?php

// https://wiki.swoole.com/#/memory/atomic?id=wait

$atomic = new Swoole\Atomic();

$serv = new Swoole\Server('127.0.0.1', '9501');

$serv->set([
    'worker_num' => 1,
    'log_file' => '/dev/null'
]);

$serv->on("start", function ($serv) use ($atomic) {
    echo "start\n";
    if ($atomic->add() == 2) {
        $serv->shutdown();
    }
});

$serv->on("ManagerStart", function ($serv) use ($atomic) {
    echo "ManagerStart\n";
    if ($atomic->add() == 2) {
        $serv->shutdown();
    }
});

$serv->on("ManagerStop", function ($serv) {
    echo "shutdown\n";
});

$serv->on("Receive", function () {
});

$serv->start();
