<?php

namespace Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class LogsController
{
    public function __construct() {}
    public function __destruct() {}

    public function showLogsPage($container, Request $request, Response $response): Response
    {
        $view      = $container->get('view');
        $settings  = $container->get('settings');
        $db        = $container->get('database');
        $logsView  = $container->get('logsView');

        $queryParams     = $request->getQueryParams();
        $selectedDeviceId = $queryParams['device_id'] ?? null;
        if ($selectedDeviceId === '') {
            $selectedDeviceId = null;
        }

        // Devices list (for dropdown) using countMessagesPerDevice()
        $devices = $db->countMessagesPerDevice() ?: [];

        // Messages: filter by device or get all
        if ($selectedDeviceId) {
            $messages = $db->getMessagesByDevice($selectedDeviceId) ?: [];
        } else {
            $messages = $db->getAllMessages() ?: [];
        }

        return $logsView->createLogsPage(
            $view,
            $response,
            $settings,
            $messages,
            $devices,
            $selectedDeviceId
        );
    }
}
