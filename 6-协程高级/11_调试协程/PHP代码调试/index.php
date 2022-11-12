<?php

use Swoole\Coroutine;

$coros = Coroutine::listCoroutines();
var_dump($coros);
foreach ($coros as $cid) {
    var_dump(Coroutine::getBackTrace($cid));
}


// ➜  learning_of_swoole git:(main) ✗ php 6-协程高级/11_调试协程/PHP代码调试/index.php
// object(Swoole\Coroutine\Iterator)#1 (1) {
//   ["storage":"ArrayIterator":private]=>
//   array(0) {
//   }
// }
// ➜  learning_of_swoole git:(main) ✗ 