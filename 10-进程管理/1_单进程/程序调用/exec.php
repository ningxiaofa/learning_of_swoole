<?php

// 例 1：可以在 Swoole\Process 创建的子进程中使用 Swoole\Server，但为了安全必须在 $process->start 创建进程后，调用 $worker->exec() 执行。代码如下：

$process = new Swoole\Process('callback_function', true);

$pid = $process->start();

function callback_function(Swoole\Process $worker)
{
    $worker->exec('/opt/homebrew/bin/php', array(__DIR__ . '/swoole_server.php'));
}

Swoole\Process::wait();

// 例 2：启动 Yii 程序
// 参见 exec_yii.php


// 例 3：父进程与 exec 子进程使用标准输入输出进行通信:
// 参见 exec_with_exec.php

// 例 4：执行 shell 命令
// shell.php