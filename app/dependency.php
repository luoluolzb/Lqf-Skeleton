<?php
declare(strict_types=1);

use Lqf\App;
use Medoo\Medoo;
use Twig\Loader\FilesystemLoader as TwigLoader;
use Twig\Environment as TwigEnv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return function (App $app) {
    $container = $app->getContainer();
    $config    = $app->getConfig();

    $container->set('medoo', function () use ($config) {
        return new Medoo($config->get('medoo'));
    });

    $container->factory('twigEnv', function () use ($config) {
        return new TwigEnv(
            new TwigLoader($config->get('twig.template_path')),
            $config->get('twig.environment_options')
        );
    });

    $container->set('logger', function() use ($config) {
        $logger = new Logger('app_logger');
        $logger->pushHandler(new StreamHandler(
            $config->get('logger.file_path'),
            $config->get('logger.level')
        ));
        return $logger;
    });
};
