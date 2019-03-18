<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once "./application/modules/admin/controllers/Admin.php";
class Actioncard extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('actioncard_model');
        $this->load->model('action_model');
        $this->load->model('site/site_model');
    }

    public function index($order = 'created_at', $order_method = 'DESC')
    {

        $action_cards = $this->actioncard_model->get_action_cards($order, $order_method);

        $v_data["order_method"] = $order_method == "DESC" ? "ASC" : "DESC";

        $v_data["action_cards"] = $action_cards;

        $data = array(
            "title" => "Action-Cards",
            "page_header" => "Groups",
            "content" => $this->load->view("actions/all_actions", $v_data, true),
            "action_cards" => $v_data["action_cards"],
            "check" => "actions",
        );
        // var_dump($data);die();

        $this->load->view('layouts/layout', $data);
    }

    public function get_responses($action_id, $order = 'created_at', $order_method = 'DESC')
    {
        $v_data["action_responses"] = $this->action_model->get_responses($order, $order_method, $action_id);

        $v_data['action_id'] = $action_id;
        
        $v_data["order_method"] = $order_method == "DESC" ? "ASC" : "DESC";
        
        $v_data['count_response'] = $this->action_model->count_response($action_id);

        $data = array(
            "title" => "Action-Responses",
            "page_header" => "Groups",
            "content" => $this->load->view("actions/all_responses", $v_data, true),
            "action_responses" => $v_data["action_responses"],
            "check" => "action_responses",
        );
        // var_dump($data);die();

        $this->load->view('layouts/layout', $data);
    }

    public function edit_package_name($action_id)
    {
        if ($this->action_model->edit_package_name($action_id)) 
        {
            $this->session->set_flashdata('success', 'Package name updated successfully');
        } 
        else 
        {
            $this->session->set_flashdata('error', 'Failed to update package name');
        }

        redirect('administration/all-actions');

    }
}