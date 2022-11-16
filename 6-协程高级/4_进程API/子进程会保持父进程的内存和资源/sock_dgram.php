<?php

use Swoole\Process;
use function Swoole\Coroutine\run;

//IPC通讯即使是 SOCK_DGRAM 类型的socket也不需要用 sendto / recvfrom 这组函数，send/recv即可。
$proc1 = new Process(function (Process $proc) {
    $socket = $proc->exportSocket();
    while (1) {
        var_dump($socket->send("hello master\n"));
    }
    echo "proc1 stop\n";
}, false, 2, 1); //构造函数pipe type传为2 即SOCK_DGRAM

$proc1->start();

run(function () use ($proc1) {
    $socket = $proc1->exportSocket();
    Swoole\Coroutine::sleep(5);
    var_dump(strlen($socket->recv())); //一次recv只会收到一个"hello master\n"字符串 不会出现多个"hello master\n"字符串
});

Process::wait(true);
