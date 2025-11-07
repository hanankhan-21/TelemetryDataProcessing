<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Helpers\Database;

return function (Container $container, App $app): void {

    $container->set(Database::class, function (ContainerInterface $c) {
        return new Database($c);
    });

    // ... other dependencies if needed
};
