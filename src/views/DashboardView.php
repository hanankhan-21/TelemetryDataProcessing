<?php 


namespace Views;

class DashboardView{

    
public function createDashboardPage($view, $response, $settings, $userName, $routes)
{
            $css_path         = $settings['css_path'] ;

    $data = [
        'page_title'      => $settings['application_name'],
        'user_name'       => $userName,
        'deviceInfoRoute' => $routes['deviceInfoRoute'],
        'userInfoRoute'   => $routes['userInfoRoute'],
        'aboutusRoute'    => $routes['aboutusRoute'],
        'logsRoute'       => $routes['logsRoute'],
        'logoutRoute'     => $routes['logoutRoute'],
            'css_path'               => $css_path,

        // icons
        'device_icon'     => '/telemetryDataProcessing2/public/assets/media/device_icon.jpg',
        'user_info_icon'  => '/telemetryDataProcessing2/public/assets/media/user_information_icon.png',
        'aboutus_icon'    => '/telemetryDataProcessing2/public/assets/media/about_icon.png',
        'logs_icon'       => '/telemetryDataProcessing2/public/assets/media/logs_icon.jpg',
    ];

    return $view->render($response, 'dashboard.html.twig', $data);
}





}