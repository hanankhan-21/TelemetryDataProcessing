<?php 

namespace Views;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;

class LoginView{


    public function __construct() {}

    public function __destruct() {}



     public function createHomePage($view, $settings, $response, $loginFailureReason){

        $css_path         = $settings['css_path'] ;
        $landing_page     = rtrim($settings['landing_page'], '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

         $data = [
            'register'               => $landing_page . '/registerUser',
            'css_path'               => $css_path,
            'landing_page'           => $landing_page,
            'action'                 => $landing_page . '/loginUser',
            'method'                 => 'post',
            'initial_input_box_value'=> null,
            'page_title'             => $application_name,
            'page_heading_1'         => $application_name,
            'login_failure_reason'   => $loginFailureReason,
        ];

        // Render Twig template and return response
        return $view->render($response, 'login.html.twig', $data);


    }

}





?>