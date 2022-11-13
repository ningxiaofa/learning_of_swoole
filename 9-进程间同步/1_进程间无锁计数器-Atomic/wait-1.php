<?php

$n = new Swoole\Atomic(1);

if (pcntl_fork() > 0) {
    echo "master start\n";
    echo "master wait: before: {$n->get()}\n";
    $n->wait(1.5);
    echo "master wait: after: {$n->get()}\n";
    echo "master end\n";
} else {
    echo "child start\n";
    // sleep(1);
    echo "child wakeup: before: {$n->get()}\n";
    $n->wakeup();
    echo "child wakeup: after: {$n->get()}\n";
    echo "child end\n";
}

