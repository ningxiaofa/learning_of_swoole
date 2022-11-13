<?php

$lock = new Swoole\Lock(SWOOLE_MUTEX);
echo "[Master]create lock\n";
$lock->lock();
if (pcntl_fork() > 0) {
    echo "[Master] sleep before\n";
    sleep(1); // 发生进程切换
    echo "[Master] sleep after\n";
    $lock->unlock(); // 发生进程切换，到子进程，唤醒lock
} else {
    echo "[Child] Wait Lock\n";
    $lock->lock(); // 发生进程切换，重新回到父进程，等待sleep执行完毕
    echo "[Child] Get Lock\n";
    $lock->unlock(); // 发生进程切换切换，到下面的代码 -- [Master]release lock
    exit("[Child] exit\n");
}
echo "[Master]release lock\n";
unset($lock);//在父子进程都释放了锁之后，开始销毁锁变量
sleep(1); // 进程切换, 重新回到子进程
echo "[Master]exit\n";
