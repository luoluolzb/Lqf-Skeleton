<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Lqf\AppFactory;
use Zend\Diactoros\RequestFactory;
use Zend\Diactoros\ResponseFactory;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\StreamFactory;
use Zend\Diactoros\UploadedFileFactory;
use Zend\Diactoros\UriFactory;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\CallbackHandler;

(function () {
    // 注入框架依赖
    AppFactory::bindRequestFactory(new RequestFactory);
    AppFactory::bindResponseFactory(new ResponseFactory);
    AppFactory::bindServerRequestFactory(new ServerRequestFactory);
    AppFactory::bindStreamFactory(new StreamFactory);
    AppFactory::bindUploadedFileFactory(new UploadedFileFactory);
    AppFactory::bindUriFactory(new UriFactory);
    $app = AppFactory::getInstance();

    // 加载应用配置
    $srcPath = __DIR__ . '/../src/';
    $app->getConfig()->loadAndMerge($srcPath . 'config.php');

    // 注册错误处理，框架不接管
    $whoops = new Whoops;
    if ($app->isDebug()) {  // 调试模式
        $whoops->appendHandler(new PrettyPageHandler);
    } else {  // 生产模式
        // 应该添加 Whoops\Handler\CallbackHandler
        // 注入自定义的处理器：如写入错误信息到日志或发送邮件等
        $callbackHandler = new CallbackHandler(
            function ($exception, $inspector, $run) {
                error_log($exception->getMessage());
            }
        );
        $whoops->appendHandler($callbackHandler);
    }
    $whoops->register();

    // 注入依赖
    (require($srcPath . 'dependency.php'))($app);

    // 注册路由
    (require($srcPath . 'route.php'))($app);

    // 注册中间件
    (require($srcPath . 'middleware.php'))($app);
            
    // 启动应用
    $app->start();
})();
