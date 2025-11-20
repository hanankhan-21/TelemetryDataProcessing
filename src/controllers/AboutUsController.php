<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AboutUsController
{
    public function __construct() {}
    public function __destruct() {}

    public function outputAboutUsPage($container, Request $request, Response $response): Response
    {
        $view     = $container->get('view');
        $settings = $container->get('settings');
        $aboutUsView = $container->get('aboutUsView');

        return $aboutUsView->createAboutUsPage($view, $response, $settings);
    }
}