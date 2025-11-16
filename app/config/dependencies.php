<?php

use Controllers\RegistrationController;
use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Helpers\Database;
use Models\LoginModel;
use Models\RegisterationModel;
use Controllers\LoginController;
use Views\LoginView;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Views\RegisterationView;

return function (Container $container, App $app): void {

    

    $container->set('view', function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    });

    // Register Database dependency
    $container->set('database', function (ContainerInterface $c) {
        return new Database($c);
    });

    // Register RegisterationModel dependency
    $container->set('registerationModel', function () {
        return new RegisterationModel();
    });

   $container->set('loginModel', function () {
        return new LoginModel();
    });

    $container->set('loginController', function () {
        return new LoginController();
    });

    $container->set('loginView', function () {
        return new LoginView();
    });

    $container->set('registerationView', function () {
        return new RegisterationView();
    });

    $container->set('registerationController', function () {
        return new RegistrationController();
    });

};
