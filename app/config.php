<?php
declare(strict_types=1);

use Monolog\Logger;

return [
    // 调试模式开关
    'debug' => true,

    // medoo 配置
    'medoo' => [
        // 必须配置项
        'database_type' => 'mysql',
        'database_name' => 'test',
        'server' => 'localhost',
        'username' => 'root',
        'password' => '123456',

        // [可选]
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'port' => 3306,

        // [可选] 定义表的前缀
        'prefix' => 'tb_',

        // [可选] 启用日志记录 (默认情况下日志是禁用以获得更好的性能)
        'logging' => false,

        // [可选] 使用 socket 连接 MySQL 
        // (如果使用socket，不应该使用 server 和 port 参数)
        // 'socket' => '/tmp/mysql.sock',

        // 连接参数扩展, 更多参考：
        // http://www.php.net/manual/en/pdo.setattribute.php
        'option' => [
            \PDO::ATTR_CASE => \PDO::CASE_NATURAL
        ],
    ],

    // twig 模板引擎配置
    'twig' => [
        // 模板目录路径
        'template_path' => __DIR__ . '/template',
        // 环境选项
        'environment_options' => [
            'debug' => true,
            'cache' => __DIR__ . '/../storage/cache/template',
        ],
    ],

    // monolog 日志配置
    'logger' => [
        // 日志文件路径
        'file_path' => __DIR__ . '/../storage/log/app.log',
        // 记录日志等级
        'level' => Logger::DEBUG,
    ],
];
