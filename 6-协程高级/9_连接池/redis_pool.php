<?php

declare(strict_types=1);

use Swoole\Coroutine;
use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool;
use Swoole\Runtime;


const N = 1024;

Runtime::enableCoroutine();
$s = microtime(true);
Coroutine\run(function () {
    $pool = new RedisPool((new RedisConfig)
            ->withHost('127.0.0.1')
            ->withPort(63790)
            ->withAuth('')
            ->withDbIndex(0)
            ->withTimeout(1)
    );
    for ($n = N; $n--;) {
        Coroutine::create(function () use ($pool) {
            $redis = $pool->get();
            $result = $redis->set('foo', 'bar');
            if (!$result) {
                throw new RuntimeException('Set failed');
            }
            $result = $redis->get('foo');
            if ($result !== 'bar') {
                throw new RuntimeException('Get failed');
            }
            $pool->put($redis);
        });
    }

    // // 调试协程
    // $coros = Coroutine::listCoroutines();
    // var_dump($coros);
    // foreach ($coros as $cid) {
    //     var_dump(Coroutine::getBackTrace($cid));
    // }

});
$s = microtime(true) - $s;

echo 'Use ' . $s . 's for ' . (N * 2) . ' queries' . PHP_EOL;
