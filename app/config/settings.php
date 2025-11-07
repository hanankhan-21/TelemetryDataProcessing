<?php

use DI\Container;

return function (Container $container, string $app_dir): void {

    // URL path (for links)
    $app_url = dirname($_SERVER['SCRIPT_NAME']);

    // Filesystem path (for includes / templates)
    $script_filename     = $_SERVER['SCRIPT_FILENAME'];
    $arr_script_filename = explode(DIRECTORY_SEPARATOR, $script_filename, -1);
    $script_path         = implode(DIRECTORY_SEPARATOR, $arr_script_filename) . DIRECTORY_SEPARATOR;

    $container->set('settings', [

        // General app info
        'application_name' => 'Telemetry Processing',
        'landing_page'     => $app_url,

        // Error / debug
        'displayErrorDetails' => true,   // set false in production
        'logErrorDetails'     => true,
        'logErrors'           => true,
        'mode'                => 'development',
        'debug'               => true,

        // Assets
        'images_path' => $app_url . '/media/',
        'css_path'    => $app_url . '/css/standard.css',

        // Database settings
        'db' => [
            'host'    => '127.0.0.1',
            'dbname'  => 'telemetry_db',
            'user'    => 'root',
            'pass'    => '',
            'charset' => 'utf8mb4',
            'port'    => '3306',
        ],

        // View / Twig / Paths
        'view' => [
            'template_path' => $app_dir . '/templates/',
            'cache_path'    => $app_dir . '/cache/',

            'twig' => [
                'cache'       => false,  // disable in dev
                'auto_reload' => true,
            ],
        ],

        // Extra paths (if you need them elsewhere)
        'paths' => [
            'app_dir'     => $app_dir,
            'config_dir'  => $app_dir . '/config/',
            'routes_dir'  => $app_dir . '/routes/',
            'script_path' => $script_path,
        ],
    ]);
};
