<?php

// https://www.php.net/manual/zh/function.proc-open.php

$descriptorspec = array(
   0 => array("pipe", "r"),  // 标准输入，子进程从此管道中读取数据
   1 => array("pipe", "w"),  // 标准输出，子进程向此管道中写入数据
   2 => array("file", "/tmp/error-output.txt", "a") // 标准错误，写入到一个文件
);

// 要执行命令的初始工作目录。 必须是 绝对 路径， 设置此参数为 null 表示使用默认值（当前 PHP 进程的工作目录）。也就是下面的 PHP的执行路径：$cwd/php [这里对应自己的执行环境]
$cwd = '/tmp'; // 
$cwd = '/opt/homebrew/bin';

$env = array('some_option' => 'aeiou');

$process = proc_open('php', $descriptorspec, $pipes, $cwd, $env);
// var_dump($pipes);
// var_dump($process);
// array(2) {
//     [0]=>
//     resource(5) of type (stream)
//     [1]=>
//     resource(6) of type (stream)
//   }
// resource(7) of type (process)

if (is_resource($process)) {
    // $pipes 现在看起来是这样的：
    // 0 => 可以向子进程标准输入写入的句柄
    // 1 => 可以从子进程标准输出读取的句柄
    // 错误输出将被追加到文件 /tmp/error-output.txt

    /** 出现报错，标准错误输出入到 /tmp/error-output.txt 文件中*/
    fwrite($pipes[0], '<?php print_r($_ENV); ?>');
    fwrite($pipes[0], '<?php print_r("iamwilliam\n"); ?>');
    fclose($pipes[0]);

    echo stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    // 切记：在调用 proc_close 之前关闭所有的管道以避免死锁。
    $return_value = proc_close($process);

    echo "command returned $return_value\n";
}

// 期望输出：
// Array
// (
//     [some_option] => aeiou
//     [PWD] => /tmp
//     [SHLVL] => 1
//     [_] => /usr/local/bin/php
// )
// iamwilliam
// command returned 0


// 实际输出：
// ➜  learning_of_swoole git:(main) ✗ php 6-协程高级/4_进程API/proc_open.php
// Array
// (
// )
// iamwilliam
// command returned 0
// ➜  learning_of_swoole git:(main) ✗ 