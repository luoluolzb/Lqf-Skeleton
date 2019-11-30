<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * 登录验证中间件
 */
class LoginValidation implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (\strpos($path, '/admin/') === 0 && $path !== '/admin/login') {
            $isLogin = isset($_COOKIE['user']) && isset($_COOKIE['password']);
            if (!$isLogin) {
                return new RedirectResponse('/admin/login', 302);
            }
        }
        return $handler->handle($request);
    }
}
