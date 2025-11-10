<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Helpers\Database;
use Models\RegisterationModel;
use Models\LoginModel;


use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;




return function (Container $container, App $app): void {

    // Register Database dependency
    $container->set(Database::class, function (ContainerInterface $c) {
        return new Database($c);
    });

    // Register RegisterationModel dependency
    $container->set(RegisterationModel::class, function () {
        return new RegisterationModel();
    });

     $container->set(LoginModel::class, function () {
        return new LoginModel();
    });
   
    $container->set('view', function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    });
};


    


