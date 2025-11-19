<?php

namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeviceInfoController{

public function __construct() {}
    public function __destruct() {}

    public function showDeviceInfo($container, Request $request, Response $response): Response
    {
        $view        = $container->get('view');
        $settings    = $container->get('settings');
        $db          = $container->get('database');
        $deviceModel = $container->get('deviceInfoModel');
        $deviceView  = $container->get('deviceInfoView');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1) Get all devices
        $devices = $deviceModel->getAvailableDevices($db);

        // 2) Selected device (from query param)
        $queryParams      = $request->getQueryParams();
        $selectedDeviceId = $queryParams['device_id'] ?? null;

        if ($selectedDeviceId === null && !empty($devices)) {
            $selectedDeviceId = $devices[0]['device_id'];
        }

        // 3) Latest message for selected device
        $latestMessage = null;
        if ($selectedDeviceId !== null) {
            $latestMessage = $deviceModel->getLatestForDevice($selectedDeviceId, $db);
        }

        $landing_page  = rtrim($settings['landing_page'], '/');
        $dashboardLink = $landing_page . '/dashboard';

        return $deviceView->createDeviceInfoPage(
            $view,
            $response,
            $settings,
            $devices,
            $selectedDeviceId,
            $latestMessage,
            $dashboardLink
        );
    }

    
}