<?php
declare(strict_types=1);

use Lqf\App;

return function (App $app) {
    $container = $app->getContainer();
    $config    = $app->getConfig();

    $container->set('medoo', function () use ($config) {
        return new \Medoo\Medoo($config['medoo']);
    });

    $container->factory('twig', function () use ($config) {
        return new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader($config['twig.template_path']),
            $config['twig.environment_options']
        );
    });

    $container->set('logger', function () use ($config) {
        $logger = new \Monolog\Logger('app_logger');
        $logger->pushHandler(
            new \Monolog\Handler\StreamHandler(
                $config['logger.file_path'],
                $config['logger.level']
            )
        );
        return $logger;
    });
};
