<?php
declare(strict_types=1);

use Lqf\App;
use App\Middleware\LoginValidation;

return function (App $app) {
    $router = $app->getRouter();

    $router->middleware(LoginValidation::class);
};
