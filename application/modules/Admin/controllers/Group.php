<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once "./application/modules/admin/controllers/Admin.php";

class Group extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model');
        $this->load->model('kaizala_model');
        $this->load->model('actioncard_model');
        $this->load->model('site/site_model');
    }

    public function index($order = 'group_name', $order_method = 'ASC')
    {
        //pagination
        $segment = 5;

        $config = array();

        $config["per_page"] = 10;
        $config['num_links'] = 5;

        $start = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;

        $where = null;

        if($this->session->userdata('search_params'))
        {
            $where = $this->session->userdata('search_params');
        }

        $result = $this->group_model->get_groups($config["per_page"], $start, $order, $order_method, $where);

        $config['full_tag_open'] = '<div class="pagging text-center"><nav aria-label="Page navigation example"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close'] = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['last_tagl_close'] = '</span></li>';

        $config["base_url"] = base_url() . "administration/all-groups/" . $order . "/" . $order_method;

        $config["total_rows"] = $this->group_model->groups_count($where);

        $this->pagination->initialize($config);

        $v_data["kaiza_groups"] = $result;

        $v_data["links"] = $this->pagination->create_links();
        
        $v_data["counter"] = $start;
        $v_data["order_method"] = $order_method == "DESC" ? "ASC" : "DESC";
        $v_data["action_cards"] = $this->actioncard_model->all_action_cards();

        $data = array(
            "title" => $this->site_model->display_page_title(),
            "page_header" => "Groups",
            "content" => $this->load->view("groups/list_groups", $v_data, true),
            "groups" => $v_data["kaiza_groups"],
            "check" => "groups"
        );
        // var_dump($data);die();

        $this->load->view('layouts/layout', $data);
    }


    public function search_groups()
    {
        $group_name = $this->input->post('group_name');
        $group_type = $this->input->post('group_type');

        if($group_name && $group_type)
        {
            $where = "group_name = '" . $group_name . "' AND group_type = '" . $group_type . "'";
        }
        else if($group_name)
        {
            $where = "group_name = '" . $group_name . "'";
        }
        else if($group_type)
        {
            $where = "group_type = '" . $group_type . "'";
        }

        $this->session->set_userdata('search_params', $where);

        redirect('administration/all-groups');
    }

    public function close_search()
    {
        $this->session->unset_userdata('search_params');

        redirect('administration/all-groups');

    }

    public function get_group_users($group_name, $group_id)
    {
        $group_name = preg_replace('/-/', ' ', $group_name);
        $group_unique_id_obj = $this->group_model->get_group_unique_id($group_id);
        $group_unique_id = $group_unique_id_obj->group_unique_id;
        
        $where = array(
            'group_unique_id' => $group_unique_id,
        );

        $users = $this->group_model->get_group_users($where);

        if ($users->num_rows() > 0) 
        {
            $this->load_users_view($users->result(), $group_name);
        }

         else 
         {
            $members = $this->kaizala_model->get_group_users($group_unique_id, 'members');

            // echo json_encode($members);die();

            if ($members != FALSE) 
            {

                $arr_members = array();

                $tests = array();

                foreach ($members as $key => $member) 
                {

                    if(array_key_exists('mobileNumber', $member))
                    {
                        $mobileNumber = $member->mobileNumber;
                    }
                    else 
                    {
                        $mobileNumber = 'not available';
                    }

                   if($mobileNumber != null || $mobileNumber != "")
                    {
                        $user_name = array_key_exists('name', $member) ? (($member->name == null || $member->name == "" ) ? 'no name' : $member->name) : '';

                        $profilePic = array_key_exists('profilePic', $member) ? (($member->profilePic == null || $member->profilePic == "" ) ? 'no profilePic' : $member->profilePic) : '';

                        array_push($arr_members, array(
                            'user_unique_id' => $member->id,
                            'group_unique_id' => $group_unique_id,
                            'user_role' => $member->role,
                            'user_mobile_number' => $mobileNumber,
                            'user_name' => $user_name,
                            'user_profile_pic' => $member->profilePic,
                            'user_is_provisioned' => $member->isProvisioned == true ? 1 : 0,
                        )
                        );
                    }

                }
                // echo json_encode($arr_members);die();

                if ($this->group_model->save_group_members($arr_members)) 
                {
                    
                    $subscribers = $this->kaizala_model->get_group_users($group_unique_id, 'subscribers');

                    // echo json_encode($subscribers);die();
                    if($subscribers != FALSE)
                    {
                        $arr_subscribers = array();
                        foreach ($subscribers as $key => $subscriber) {
        
                            array_push($arr_subscribers, array(
                                'user_unique_id' => $subscriber->id,
                                'group_unique_id' => $group_unique_id,
                                'user_role' => 'Subscriber',
                                'user_mobile_number' => $subscriber->mobileNumber,
                                'user_name' => $subscriber->name,
                                'user_profile_pic' => $subscriber->profilePic,
                                'user_is_provisioned' => $subscriber->isProvisioned == true ? 1 : 0,
                                )
                            );
        
                        }
                        if($this->group_model->save_group_members($arr_subscribers))
                        {
                            $users = $this->group_model->get_group_users($where);
                        }
                        else
                        {
                            $users = $this->group_model->get_group_users($where);
                        }
                    }
                    else 
                    {
                        $users = $this->group_model->get_group_users($where);
                    }
                } 
                
                redirect('administration/group-users/'.preg_replace('/\s/', '-', $group_name).'/'.$group_id);
            }
            else {
                $this->session->set_flashdata('error', "unable to query users");
            }

            redirect('administration/all-groups');
        }
    }

    private function load_users_view($users, $group_name)
    {
        
        $v_data['check'] = true;
        $v_data['users'] = $users;
        $v_data['page_header'] = "Users of " . $group_name;

        $data = array(
            "title" => $this->site_model->display_page_title(),
            "page_header" => "Users of " . $group_name,
            "content" => $this->load->view("users/list_users", $v_data, true),
            "users" => $v_data["users"],
            "check" => "users"
        );
        // var_dump($data);die();

        $this->load->view('layouts/layout', $data);
    }

    public function fetch_groups()
    {
        $result = $this->kaizala_model->fetch_groups();

        if($result[0] == TRUE)
        {
            $groups = $result[1];

            $model_result = $this->group_model->save_group($groups);
            // var_dump($model_result);die();
            if($model_result == 0)
            {
                $this->session->set_flashdata('error', "No Group was Fetched!!");
            }
            else if($model_result > 0)
            {
                $this->session->set_flashdata('success', $model_result . " new Groups were fetched successfully!!");
            }
            else if($model_result == "FAILED")
            {
                $this->session->set_flashdata('error', "Failed to fetch some of groups!!");
            }
            
        }
        else 
        {
            $this->session->set_flashdata('error', $result[1]->message);
        }

        redirect('administration/all-groups');
    }

    public function activate_group($group_id)
    {
        //get group-unique id
        $group_unique_id_obj = $this->group_model->get_group_unique_id($group_id);
        $group_unique_id = $group_unique_id_obj->group_unique_id;

        // $webhooks = $this->kaizala_model->all_webhooks($group_unique_id);

        // echo json_encode($webhooks);die();
        
        $result = $this->kaizala_model->create_event_webhook($group_unique_id, base_url());

        if($result[0] == TRUE)
        {
            $webhook_id = $result[1];
            $activate_status = $this->group_model->activate_group($group_id, $webhook_id);

            if($activate_status)
            {
                $this->session->set_flashdata('success', 'Activated Successfully!!');
            }
            else
            {
                $this->session->set_flashdata('success', 'Unable to activate!!');
            }
        }
        else 
        {
            $this->session->set_flashdata('error', $result[1]->message);
        }

        redirect('administration/all-groups');
    }

    public function deactivate_group($group_id)
    {
        //get group-unique id
        $webhook_id_obj = $this->group_model->get_webhook_id($group_id);
        $webhook_id = $webhook_id_obj->webhook_id;

        // echo json_encode($webhook_id);die();
        
        $result = $this->kaizala_model->delete_event_webhook($webhook_id);

        if($result[0] == TRUE)
        {
            $activate_status = $this->group_model->deactivate_group($group_id);

            if($activate_status)
            {
                $this->session->set_flashdata('success', 'Deactivated Successfully!!');
            }
            else
            {
                $this->session->set_flashdata('success', 'Unable to deactivate!!');
            }
        }
        else 
        {
            $this->session->set_flashdata('error', $result[1]->message);
        }

        redirect('administration/all-groups');
    }
}