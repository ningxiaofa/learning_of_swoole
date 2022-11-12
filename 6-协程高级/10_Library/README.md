Library
https://wiki.swoole.com/#/library?id=library
https://github.com/swoole/library/tree/master/examples

Swoole 在 v4 版本后内置了 Library 模块，使用 PHP 代码编写内核功能，使得底层设施更加稳定可靠

该模块也可通过 composer 单独安装，单独安装使用时需要通过 php.ini 配置 swoole.enable_library=Off 关闭扩展内置的 library

目前提供了以下工具组件：

Coroutine\WaitGroup 用于等待并发协程任务，文档

Coroutine\FastCGI FastCGI 客户端，文档

Coroutine\Server 协程 Server，文档

Coroutine\Barrier 协程屏障，文档

CURL hook CURL 协程化，文档

Database 各种数据库连接池和对象代理的高级封装，文档

ConnectionPool 原始连接池，文档

Process\Manager 进程管理器，文档

StringObject 、ArrayObject 、MultibyteStringObject 面向对象风格的 Array 和 String 编程

functions 提供的一些协程函数，文档

Constant 常用配置常量

HTTP Status HTTP 状态码

示例代码
Examples

参见
[learning_of_swoole_library](https://github.com/swoole/library)

