<?php

// 注意：
// 主要是使用Nginx + PHP-FPM做测试[这里主要是通过mac下Docker环境，性能会有影响]
// 有些测试，可以放到CLI下测试，也可以使用PHP-FPM测试，
// 但是有些测试，不论是用哪种方式，都会报错[相同的报错]
// 如：TCP会报错
// [
    // Warning: stream_socket_accept(): accept failed: Connection timed out
    // Warning: stream_socket_client(): unable to connect to tcp://127.0.0.1:9502 (Connection refused) 
// ]
// 原因: 应该是这种方式TCP Server同一时间只能接受一个客户端请求
// 更多：TBD

$s = microtime(true);

// ------------------------------------
// i just want to sleep...
for ($c = 100; $c--;) {
    for ($n = 100; $n--;) {
        usleep(1000); // 1000微妙，即1ms
        // echo $c * $n . PHP_EOL;
    }
}
// 理论分析: 应该是100*100=10000微妙 = 10s+
// 测试结果：[如同分析]
// use 12.47922205925 s

// ------------------------------------
// 10k file read and write
for ($c = 100; $c--;) {
    $tmp_filename = "/tmp/test-{$c}.php";
    for ($n = 100; $n--;) {
        $self = file_get_contents(__FILE__);
        file_put_contents($tmp_filename, $self);
        assert(file_get_contents($tmp_filename) === $self);
    }
    unlink($tmp_filename);
}

// use 0.70789790153503 s

// ------------------------------------
// 10k pdo and mysqli read
for ($c = 50; $c--;) {
    // localhost[cli]
    // $pdo = new PDO('mysql:host=127.0.0.1;port=33060;dbname=test;charset=utf8', 'root', 'root');
    // php-fpm[docker]
    $pdo = new PDO('mysql:host=172.18.0.4;port=3306;dbname=test;charset=utf8', 'root', 'root');
    $statement = $pdo->prepare('SELECT * FROM `user`');
    for ($n = 100; $n--;) {
        $statement->execute();
        
        $ret = $statement->fetchAll();
        // var_dump($ret) . PHP_EOL;
        // echo assert(count($statement->fetchAll()) > 0); // 在终端下输出，更加明显
        assert(count($ret) > 0);
    }
}
for ($c = 50; $c--;) {
     // localhost[cli]
    // $mysqli = new Mysqli('127.0.0.1:33060', 'root', 'root', 'test');
    // php-fpm[docker]
    $mysqli = new Mysqli('172.18.0.4:3306', 'root', 'root', 'test');
    $statement = $mysqli->prepare('SELECT `id` FROM `user`');
    for ($n = 100; $n--;) {
        $statement->bind_result($id);
        $statement->execute();
        $statement->fetch();
        // echo assert($id > 0); // 在终端下输出，更加明显
        assert($id > 0);
    }
}
// use 7.3599529266357 s

// 上面一起执行：
// CLI: use 19.765893936157 s
// PHP-FPM: use 20.582198143005 s

// ------------------------------------ 在PHP下出现报错，原因和解决办法:TBD[Need to learn stream module of PHP]
// php_stream tcp server & client with 12.8k requests in single process
function tcp_pack(string $data): string
{
    return pack('n', strlen($data)) . $data;
}

function tcp_length(string $head): int
{
    return unpack('n', $head)[1];
}

function tcp_server()
{
    $ctx = stream_context_create(['socket' => ['so_reuseaddr' => true, 'backlog' => 128]]);
    $socket = stream_socket_server(
        'tcp://0.0.0.0:9502',
        $errno,
        $errstr,
        STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
        $ctx
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
}
// 在CLI下，将同时请求数量改为1[$c=1, $n=1]，则不会出现报错
function tcp_client()
{
    for ($c = 128; $c--;) {
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
    }
}
// tcp_server();
// tcp_client();

// ------------------------------------
// udp server & client with 12.8k requests in single process
function udp_server() {
    // 因为swoole实现的udp server使用的是swoole提供的 
    // PHP实现TBD
}
function udp_client()
{
    for ($c = 128; $c--;) {
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
    }
}
// udp_server();
// udp_client();
// ------------------------------------
echo 'use ' . (microtime(true) - $s) . ' s';
