<?php 

namespace Views;

class RegisterationView
{
    public function createRegisterationPage($view, $response, $settings, $registerationFailed = false, $registeration_failure_reason = '')
    {
        $css_path         = $settings['css_path'];
        $landing_page     = rtrim($settings['landing_page'], '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

        $data = [
            'css_path'                     => $css_path,
            'landing_page'                 => $landing_page,
            'action'                       => $landing_page . '/registerUser',
            'method'                       => 'post',
            'initial_input_box_value'      => null,
            'page_title'                   => $application_name,
            'page_heading_1'               => $application_name,
            'registeration_failed'         => $registerationFailed,
            'registeration_failure_reason' => $registeration_failure_reason,
            'login'                        => $landing_page . '/',
        ];

        
        // This should now render your Twig template
        return $view->render($response, 'registration.html.twig', $data);
    }
}








?>