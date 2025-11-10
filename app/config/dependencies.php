<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Helpers\Database;
use Models\RegisterationModel;

return function (Container $container, App $app): void {

    // Register Database dependency
    $container->set(Database::class, function (ContainerInterface $c) {
        return new Database($c);
    });

    // Register RegisterationModel dependency
    $container->set(RegisterationModel::class, function () {
        return new RegisterationModel();
    });

};
