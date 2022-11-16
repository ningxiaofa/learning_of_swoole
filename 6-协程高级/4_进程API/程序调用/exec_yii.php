<?php

$process = new \Swoole\Process(function (\Swoole\Process $childProcess) {
    // 不支持这种写法
    // $childProcess->exec('/usr/local/bin/php /var/www/project/yii-best-practice/cli/yii t/index -m=123 abc xyz');

    // 封装 exec 系统调用
    // 绝对路径
    // 参数必须分开放到数组中
    $childProcess->exec('/opt/homebrew/bin/php', ['/Users/huangbaoyin/Documents/env/docker-lnmp-dev-env-sh/html/yii2.test/yii', 'index/list', 'm=123', 'abc', 'xyz']); // exec 系统调用

    // 执行结果：
    // ➜  learning_of_swoole git:(main) ✗ string(5) "m=123"
    // string(3) "abc"
    // ➜  learning_of_swoole git:(main) ✗ 
});

$process->start(); // 启动子进程

// 先确认yii是有相应的控制器方法
// php yii // 直接回车即可
