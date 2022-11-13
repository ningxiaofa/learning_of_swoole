<?php

echo "start \n";
if (pcntl_fork() > 0) {
    echo "[Master] sleep before\n";
    echo "[Master] sleep after\n";
    echo ("[Master] exit\n");
} else {
    echo "[Child] sleep before\n";
    echo "[Child] sleep after\n";
    echo ("[Child] exit\n");
}

echo "[Common] after if/else \n";
echo "[Common] exit\n";


// ➜  learning_of_swoole git:(main) ✗ php 9-进程间同步/2_进程间锁-Lock/example-1.php
// start 
// [Master] sleep before
// [Master] sleep after
// [Master] exit
// [Common] after if/else 
// [Common] exit
// [Child] sleep before
// [Child] sleep after
// [Child] exit
// [Common] after if/else 
// [Common] exit
// ➜  learning_of_swoole git:(main) ✗ 

// 可以知道，只有条件分支中的语句才是父子进程各自独有的指令，后面的都是公共的脚本指令，都会执行，除非在条件分支中exit('终止进程');