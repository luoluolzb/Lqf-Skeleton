<?php
declare(strict_types=1);

use Lqf\App;

return function (App $app) {
    $router = $app->getRouter();

    // 注册路由中间件
    $router->middleware(\App\Middleware\LoginValidation::class);
};
