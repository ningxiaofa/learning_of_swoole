<?php

$redis = new Redis;
$redis->connect('127.0.0.1', 63790);

function callback_function()
{
    swoole_timer_after(1000, function () {
        echo "hello world\n";
    });
    global $redis; //同一个连接
};

swoole_timer_tick(1000, function () {
    echo "parent timer\n";
}); //不会继承

Swoole\Process::signal(SIGCHLD, function ($sig) {
    while ($ret = Swoole\Process::wait(false)) {
        // create a new child process
        $p = new Swoole\Process('callback_function');
        $p->start();
    }
});

// create a new child process
$p = new Swoole\Process('callback_function');

$p->start();
