<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('auth/auth_model');

        // if (!$this->auth_model->check_if_loggedin()) {
        //     redirect('login');
        // }
    }

    public function index()
    {
        redirect('administration/all-groups');
    }
}