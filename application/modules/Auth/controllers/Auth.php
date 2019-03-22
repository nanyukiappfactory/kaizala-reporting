<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
    {
        if ($this->auth_model->check_if_loggedin()) {
            redirect('administration');
        } else {
            redirect('login');
        }
    }

    public function login()
    {
        if (isset($_GET["code"])) {
            // echo 23;die();
            $redirect_uri = $this->auth_model->login_user($_GET["code"]);

            if ($redirect_uri) {
                redirect('administration');

            }

        } else {
            $accessUrl = $this->auth_model->get_authorization_url();

            header('Location: ' . $accessUrl);
        }

    }

    public function logout()
    {
        $this->session->sess_destroy();

        redirect('login');
    }
}