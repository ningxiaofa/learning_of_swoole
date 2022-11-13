<?php

echo "start \n";
if (pcntl_fork() > 0) {
    echo "[Master] sleep before\n";
    sleep(1); // 发生进程切换
    echo "[Master] sleep after\n";
    echo ("[Master] exit\n");
} else {
    echo "[Child] sleep before\n";
    sleep(1); // 发生进程切换
    echo "[Child] sleep after\n";
    echo ("[Child] exit\n");
}
echo "[Common] after if/else \n";
sleep(1); // 进程切换, 重新回到子进程
echo "[Common] exit\n";


// ➜  learning_of_swoole git:(main) ✗ php 9-进程间同步/2_进程间锁-Lock/example-1.php
// start 
// [Master] sleep before
// [Child] sleep before
// [Child] sleep after
// [Child] exit
// [Common] after if/else 
// [Master] sleep after
// [Master] exit
// [Common] after if/else 
// [Common] exit
// [Common] exit
// ➜  learning_of_swoole git:(main) ✗ 

