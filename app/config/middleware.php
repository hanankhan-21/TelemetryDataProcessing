<?php

use Slim\App;

return function (App $app): void {
    // Slim’s routing middleware must be added before $app->run()
    $app->addRoutingMiddleware();

    // Optional error middleware
  $app->addErrorMiddleware(true, true, true);



};
