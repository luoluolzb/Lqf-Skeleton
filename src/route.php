<?php
declare(strict_types=1);

use Lqf\App;
use Lqf\Route\Collector;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

return function (App $app) {
    $router    = $app->getRouter();
    $container = $app->getContainer();
    $config    = $app->getConfig();

    $router->get('/', function (Request $request): Response {
        return new HtmlResponse('<h1>lqf test</h1>');
    });

    $router->get('/hello[/{name}]', function (Request $request, array $params): Response {
        $name = $params['name'] ?? 'World';
        return new TextResponse("Hello, {$name}!");
    });

    /**
     * RESTful API
     */
    $router->group('/user', function (Collector $collector) {
        $controller = \App\Controller\UserController::class;
        $collector->post('/add', "{$controller}::add")
                  ->delete('/delete/{id:\d+}', "{$controller}::delete")
                  ->put('/update/{id:\d+}', "{$controller}::update")
                  ->get('/get/{id:\d+}', "{$controller}::get")
                  ->get('/all', "{$controller}::all");
    });

    /**
     * twig 模板引擎测试
     */
    $router->get('/twig/index', function (Request $request) use ($container): Response {
        $html = $container->get('twig')->render('index.html', ['name' => 'twig']);
        return new HtmlResponse($html);
    });

    /**
     * 登录和注销登录
     */
    $router->group('/admin', function (Collector $collector) use ($container) {
        
        $collector->get('/index', function (Request $request): Response {
            return new TextResponse('logined');
        });

        $collector->get('/login', function (Request $request) use ($container): Response {
            $html = $container->get('twig')->render('login.html');
            return new HtmlResponse($html);
        });

        $collector->post('/login', function (Request $request): Response {
            $params = $request->getParsedBody();
            \setcookie('user', $params['user']);
            \setcookie('password', $params['password']);
            return new RedirectResponse('/admin/index', 302);
        });

        $collector->get('/logout', function (Request $request): Response {
            \setcookie('user', '', time() - 1);
            \setcookie('password', '', time() - 1);
            return new RedirectResponse('/admin/login', 302);
        });
    });

    /**
     * 查看全部配置参数
     */
    $router->get('/config/all', function (Request $request) use ($config): Response {
        return new JsonResponse($config->all());
    });

    /**
     * 查看某个配置参数
     */
    $router->get('/config/get', function (Request $request) use ($config): Response {
        return new TextResponse($config->get('medoo.server'));
    });

    /**
     * 记录日志
     */
    $router->get('/log/{level:[a-z]+}/{content}', function (Request $request, array $params) use ($container): Response {
        \call_user_func([$container->get('logger'), $params['level']], $params['content']);
        return new JsonResponse($params);
    });
};
