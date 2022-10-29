<?php

// https://wiki.swoole.com/#/start/coroutine
// 使用协程你可以在一秒钟里做多少事？
// 睡眠 1 万次，读取，写入，检查和删除文件 1 万次，使用 PDO 和 MySQLi 与数据库通信 1 万次，创建 TCP 服务器和多个客户端相互通信 1 万次，创建 UDP 服务器和多个客户端相互通信 1 万次...... 一切都在一个进程中完美完成！

use Swoole\Runtime;
use Swoole\Coroutine;
use function Swoole\Coroutine\run;

// 此行代码后，文件操作，sleep，Mysqli，PDO，streams等都变成异步IO，见'一键协程化'章节
Runtime::enableCoroutine();
$s = microtime(true);

// Swoole\Coroutine\run()见'协程容器'章节
run(function() {
    // i just want to sleep...
    for ($c = 100; $c--;) {
        Coroutine::create(function () {
            for ($n = 100; $n--;) {
                usleep(1000);
            }
        });
    }

    // 10k file read and write
    for ($c = 100; $c--;) {
        Coroutine::create(function () use ($c) {
            $tmp_filename = "/tmp/test-{$c}.php";
            for ($n = 100; $n--;) {
                $self = file_get_contents(__FILE__);
                file_put_contents($tmp_filename, $self);
                assert(file_get_contents($tmp_filename) === $self);
            }
            unlink($tmp_filename);
        });
    }

    // 10k pdo and mysqli read
    for ($c = 50; $c--;) {
        Coroutine::create(function () {
            $pdo = new PDO('mysql:host=127.0.0.1;port=33060;dbname=test;charset=utf8', 'root', 'root');
            $statement = $pdo->prepare('SELECT * FROM `user`');
            for ($n = 100; $n--;) {
                $statement->execute();
                
                $ret = $statement->fetchAll();
                // var_dump($ret) . PHP_EOL;
                // echo assert(count($statement->fetchAll()) > 0); // 在终端下输出，更加明显
                assert(count($ret) > 0);
            }
        });
    }
    for ($c = 50; $c--;) {
        Coroutine::create(function () {
            $mysqli = new Mysqli('127.0.0.1:33060', 'root', 'root', 'test');
            $statement = $mysqli->prepare('SELECT `id` FROM `user`');
            for ($n = 100; $n--;) {
                $statement->bind_result($id);
                $statement->execute();
                $statement->fetch();
                // echo assert($id > 0); // 在终端下输出，更加明显
                assert($id > 0);
            }
        });
    }

    // php_stream tcp server & client with 12.8k requests in single process
    function tcp_pack(string $data): string
    {
        return pack('n', strlen($data)) . $data;
    }

    function tcp_length(string $head): int
    {
        return unpack('n', $head)[1];
    }

    Coroutine::create(function () {
        $ctx = stream_context_create(['socket' => ['so_reuseaddr' => true, 'backlog' => 128]]);
        $socket = stream_socket_server(
            'tcp://0.0.0.0:9502',
            $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $ctx
        );
        if (!$socket) {
            echo "{$errstr} ({$errno})\n";
        } else {
            $i = 0;
            while ($conn = stream_socket_accept($socket, 1)) {
                stream_set_timeout($conn, 5);
                for ($n = 100; $n--;) {
                    $data = fread($conn, tcp_length(fread($conn, 2)));
                    assert($data === "Hello Swoole Server #{$n}!");
                    fwrite($conn, tcp_pack("Hello Swoole Client #{$n}!"));
                }
                if (++$i === 128) {
                    fclose($socket);
                    break;
                }
            }
        }
    });
    for ($c = 128; $c--;) {
        Coroutine::create(function () {
            $fp = stream_socket_client('tcp://127.0.0.1:9502', $errno, $errstr, 1);
            if (!$fp) {
                echo "{$errstr} ({$errno})\n";
            } else {
                stream_set_timeout($fp, 5);
                for ($n = 100; $n--;) {
                    fwrite($fp, tcp_pack("Hello Swoole Server #{$n}!"));
                    $data = fread($fp, tcp_length(fread($fp, 2)));
                    // echo $data . PHP_EOL;
                    assert($data === "Hello Swoole Client #{$n}!");
                }
                fclose($fp);
            }
        });
    }

    // udp server & client with 12.8k requests in single process
    Coroutine::create(function () {
        $socket = new Swoole\Coroutine\Socket(AF_INET, SOCK_DGRAM, 0);
        $socket->bind('127.0.0.1', 9503);
        $client_map = [];
        for ($c = 128; $c--;) {
            for ($n = 0; $n < 100; $n++) {
                $recv = $socket->recvfrom($peer);
                $client_uid = "{$peer['address']}:{$peer['port']}";
                $id = $client_map[$client_uid] = ($client_map[$client_uid] ?? -1) + 1;
                assert($recv === "Client: Hello #{$id}!");
                $socket->sendto($peer['address'], $peer['port'], "Server: Hello #{$id}!");
            }
        }
        $socket->close();
    });
    for ($c = 128; $c--;) {
        Coroutine::create(function () {
            $fp = stream_socket_client('udp://127.0.0.1:9503', $errno, $errstr, 1);
            if (!$fp) {
                echo "$errstr ($errno)\n";
            } else {
                for ($n = 0; $n < 100; $n++) {
                    fwrite($fp, "Client: Hello #{$n}!");
                    $recv = fread($fp, 1024);
                    // echo $recv . PHP_EOL;
                    list($address, $port) = explode(':', (stream_socket_get_name($fp, true)));
                    assert($address === '127.0.0.1' && (int)$port === 9503);
                    assert($recv === "Server: Hello #{$n}!");
                }
                fclose($fp);
            }
        });
    }
});
echo 'use ' . (microtime(true) - $s) . ' s';

// Test result:
// [i just want to sleep...]: use 0.12286305427551 s
// [10k file read and write]: use 1.1645660400391 s
// [10k pdo and mysqli read]: use 0.69978499412537 s
// [php_stream tcp server & client with 12.8k requests in single process]: use 0.24558997154236 s
// [udp server & client with 12.8k requests in single process]: use 0.095209836959839 s
// use 0.21714091300964 s // 这是在与执行sleep一起的花费的时间[0.12286305427551 + 0.095209836959839 = 0.21807289123535] 差不多

// 一起执行花费的时间: [记录5次]
// use 2.0318250656128 s
// use 1.7070729732513 s
// use 1.3593230247498 s
// use 1.9122369289398 s
// use 1.6968929767609 s

// 初步看，性能挺好的
// 但是还是要结合PHP-FPM对照实验才更有说服力
