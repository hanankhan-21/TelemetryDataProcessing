<?php


namespace Views;
class LogsView{

public function createLogsPage($view, $response, $settings, array $messages, array $devices, ?string $selectedDeviceId)
    {
        $css_path         = $settings['css_path'];
        $landing_page     = rtrim($settings['landing_page'], '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

        $data = [
            'css_path'          => $css_path,
            'landing_page'      => $landing_page,
            'page_title'        => $application_name,
            'messages'          => $messages,
            'devices'           => $devices,
            'selectedDeviceId'  => $selectedDeviceId,
            'logsRoute'         => $landing_page . '/logs',
        ];

        return $view->render($response, 'logs.html.twig', $data);
    }

    
}