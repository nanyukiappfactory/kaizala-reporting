<?php

class Auth_model extends CI_Model
{
    public $client_id;
    public $secret;
    public $redirect_uri;
    public $tenant_id;
    public $authority;
    public $scopes;
    public $auth_url;
    public $token_url;
    public $api_url;

    public function __construct()
    {
        $this->client_id = $this->config->item('client_id');
        $this->secret = $this->config->item('secret');
        $this->redirect_uri = $this->config->item('redirect_uri');
        $this->tenant_id = $this->config->item('tenant_id');

        $this->authority = 'https://login.microsoftonline.com';

        $this->scopes = array("offline_access", "openid");

        // $this->scops = array("openid");

        // /* If you need to read email, then need to add following scope */
        if (true) {
            array_push($this->scopes, "https://outlook.office.com/mail.read");
        }
        /* If you need to send email, then need to add following scope */
        if (true) {
            array_push($this->scopes, "https://outlook.office.com/mail.send");
        }

        // $tenant = '/common'; // Allows users with MSAs to sign into the app
        // $tenant = '/organizations'; // Allows only users with work/school MSAs to sign into the app
        // $tenant = '/consumers'; // Allows users with personnel MSAs to sign into the app

        //$tenant = '/contoso.onmicrosoft.com'; // Allows only users with MSAs from a particular Azure AD tenant to sign into the app  or //
        $tenant = "/" . $this->config->item('tenant_id');

        //authentication URL
        $this->auth_url = "/common/oauth2/v2.0/authorize";
        $this->auth_url .= "?client_id=" . $this->client_id;
        $this->auth_url .= "&redirect_uri=" . $this->redirect_uri;
        $this->auth_url .= "&response_type=code&scope=" . implode(" ", $this->scopes);

        //token URL
        $this->token_url = $tenant . "/oauth2/v2.0/token";

        //api URL
        $this->api_url = "https://outlook.office.com/api/v2.0";
    }

    public function check_if_loggedin()
    {
        if ($this->get_token()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_authorization_url()
    {
        return $this->authority . $this->auth_url;
    }

    public function login_user($code)
    {
        // $grant_type = "client_credentials";
        $grant_type = "authorization_code";

        $token_request_data = array(
            "grant_type" => $grant_type,
            "code" => $code,
            "redirect_uri" => $this->redirect_uri,
            "scope" => implode(" ", $this->scopes),
            "client_id" => $this->client_id,
            "client_secret" => $this->secret,
        );
        $body = http_build_query($token_request_data);
        $url = $this->authority . $this->token_url;

        $result = $this->run_curl($url, $body);

        if ($result[0] == 'success') {
            $response = json_decode($result[1]);

            $this->session->set_userdata('office_access_token', $response->access_token);
            $this->store_token($response);
            $this->get_user_profile();

            return array(
                'success',
                $this->redirect_uri,
            );
        } else if ($result[0] == 'error') {
            return array(
                'error',
                $result[1],
                $result[2],
            );
        }

    }

    //get token and bearer from the session
    private function get_token()
    {
        $login_user_token = $this->session->userdata('login_user_token');

        $response_text = $login_user_token ? $login_user_token : null;

        if ($response_text != null && strlen($response_text) > 0) {
            return json_decode($response_text);
        }
        return null;
    }

    //store token to session
    private function store_token($response)
    {
        // echo json_encode($response);die();
        $this->session->set_userdata('login_user_token', json_encode($response));
    }

    private function run_curl($url, $post = null, $headers = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $post == null ? 0 : 1);
        if ($post != null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($headers != null) {
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code >= 400) {
            if ($http_code == 400) {
                $http_code = "400 Access Denied!!";
                $message = 'The grant was obtained for a different tenant';
            } else if ($http_code == 404) {
                $http_code = "404 Not Found";
                $message = 'Requested page not found!';
            } else {
                $message = $response;
            }

            return array(
                'error',
                $http_code,
                $message,
            );
        }

        return array(
            'success',
            $response,
        );
    }

    private function get_user_profile()
    {
        $headers = array(
            "User-Agent: php-tutorial/1.0",
            "Authorization: Bearer " . $this->get_token()->access_token,
            "Accept: application/json",
            "client-request-id: " . $this->make_guid(),
            "return-client-request-id: true",
        );

        $outlookApiUrl = $this->api_url . "/Me";
        // echo json_encode($headers);die();
        $result = $this->run_curl($outlookApiUrl, null, $headers);

        $response = explode("\n", trim($result[1]));
        $response = $response[count($response) - 1];

        $user_details = json_decode($response);
        // echo json_encode($user_details);die();
        $display_name = $user_details->DisplayName;
        $email_address = $user_details->EmailAddress;

        $this->session->set_userdata('display_name', $display_name);
        $this->session->set_userdata('email_address', $email_address);

        $response = json_decode($response);
    }

    private function make_guid()
    {
        if (function_exists('com_create_guid')) {
            error_log("Using 'com_create_guid'.");
            return strtolower(trim(com_create_guid(), '{}'));
        } else {
            $charid = strtolower(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
            return $uuid;
        }
    }
}
