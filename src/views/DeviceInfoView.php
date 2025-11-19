<?php


namespace Views;

class DeviceInfoView{



    public function createDeviceInfoPage(
        $view,
        $response,
        array $settings,
        array $devices,
        ?string $selectedDeviceId,
        array|false $latestMessage,
        string $dashboardLink
    ) {
        $css_path         = $settings['css_path'];
        $landing_page     = rtrim($settings['landing_page'], '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

        $data = [
            'css_path'           => $css_path,
            'landing_page'       => $landing_page,
            'page_title'         => $application_name . ' - Device Info',
            'devices'            => $devices,
            'selected_device_id' => $selectedDeviceId,
            'latest_message'     => $latestMessage,
            'dashboard_link'     => $dashboardLink,
        ];

        return $view->render($response, 'device_info.html.twig', $data);
    }


}