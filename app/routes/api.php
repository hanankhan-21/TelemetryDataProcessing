<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->post('/api/telemetry', function (Request $request, Response $response) use ($app) {

    $data = (array) $request->getParsedBody();

    $container = $app->getContainer();
    $db        = $container->get('database');

    // Extract fields from payload
    $deviceId          = $data['device_id']          ?? null;
    $switch1           = $data['switch1']            ?? null;
    $switch2           = $data['switch2']            ?? null;
    $switch3           = $data['switch3']            ?? null;
    $switch4           = $data['switch4']            ?? null;
    $fan               = $data['fan']                ?? null;
    $deviceTemperature = $data['device_temperature'] ?? null;
    $lastKeyEntered    = $data['last_key_entered']   ?? null;

    // Basic validation – you can make this stricter later
    if ($deviceId === null) {
        $response->getBody()->write(json_encode([
            'success' => false,
            'error'   => 'device_id is required',
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    // Use server time as received_date
    $receivedDate = date('Y-m-d H:i:s');

    // Store message using your helper
    $stored = $db->storeMessages(
        $deviceId,
        $switch1,
        $switch2,
        $switch3,
        $switch4,
        $fan,
        $deviceTemperature,
        $lastKeyEntered,
        $receivedDate
    );

    if (!$stored) {
        $response->getBody()->write(json_encode([
            'success' => false,
            'error'   => $db->getLastError() ?: 'Failed to store telemetry message',
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    // Success JSON response
    $response->getBody()->write(json_encode([
        'success' => true,
        'message' => 'Telemetry stored successfully',
    ]));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});
