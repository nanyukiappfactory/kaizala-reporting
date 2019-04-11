<?php

class Auth_model extends CI_Model
{
    public $client_id;
    public $secret;
    public $redirect_uri;
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
        
        $this->authority = 'https://login.microsoftonline.com';

        $this->scopes = array("offline_access", "openid");

        /* If you need to read email, then need to add following scope */
        if (true) {
            array_push($this->scopes, "https://outlook.office.com/mail.read");
        }
        /* If you need to send email, then need to add following scope */
        if (true) {
            array_push($this->scopes, "https://outlook.office.com/mail.send");
        }

        //authentication URL
        $this->auth_url = "/common/oauth2/v2.0/authorize";
        $this->auth_url .= "?client_id=" . $this->client_id;
        $this->auth_url .= "&redirect_uri=" . $this->redirect_uri;
        $this->auth_url .= "&response_type=code&scope=" . implode(" ", $this->scopes);

        //token URL
        $this->token_url = "/common/oauth2/v2.0/token";

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
        $token_request_data = array(
            "grant_type" => "authorization_code",
            "code" => $code,
            "redirect_uri" => $this->redirect_uri,
            "scope" => implode(" ", $this->scopes),
            "client_id" => $this->client_id,
            "client_secret" => $this->secret,
        );
        $body = http_build_query($token_request_data);
        $url = $this->authority . $this->token_url;
        $response = $this->run_curl($url, $body);
        $response = json_decode($response);

        $this->session->set_userdata('office_access_token', $response->access_token);
        $this->store_token($response);
        $this->get_user_profile();

        return $this->redirect_uri;
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
            echo "Error executing request to Office365 api with error code=$http_code<br/><br/>\n\n";
            echo "<pre>";
            print_r($response);
            echo "</pre>";
            die();
        }
        return $response;
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
        $response = $this->run_curl($outlookApiUrl, null, $headers);

        $response = explode("\n", trim($response));
        $response = $response[count($response) - 1];

        $user_details = json_decode($response);
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