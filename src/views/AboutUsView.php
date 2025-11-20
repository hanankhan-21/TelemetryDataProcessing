<?php

namespace Views;

class AboutUsView
{
    public function createAboutUsPage($view, $response, $settings)
    {
        $css_path         = $settings['css_path'];
        $landing_page     = rtrim($settings['landing_page'], '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

        $data = [
            'css_path'     => $css_path,
            'landing_page' => $landing_page,
            'page_title'   => $application_name,
        ];

        return $view->render($response, 'aboutus.html.twig', $data);
    }
}