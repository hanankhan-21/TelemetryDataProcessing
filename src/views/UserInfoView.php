<?php


namespace Views;


class UserInfoView{

 public function __construct() {}
    public function __destruct() {}

    /**
     * Render the User Info page
     *
     * @param \Slim\Views\Twig $view
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $settings
     * @param array $user       // associative array: full_name, email, phone_number, last_login, created_at
     * @param string|null $backLink  // optional: URL for "Back to Dashboard"
     */
    public function showUserInfo($view, $response, array $settings, array $user, ?string $backLink = null)
    {
        $css_path         = $settings['css_path'] ?? '';
        $landing_page     = rtrim($settings['landing_page'] ?? '', '/');
        $application_name = $settings['application_name'] ?? 'Telemetry Processing';

        $data = [
            'css_path'     => $css_path,
            'landing_page' => $landing_page,
            'page_title'   => $application_name . ' - User Info',
            'user'         => $user,
            'back_link'    => $backLink,
        ];

        return $view->render($response, 'user_info.html.twig', $data);
    }


}




?>