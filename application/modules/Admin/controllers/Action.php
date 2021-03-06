<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Action extends CI_Controller
{
    public $upload_path;
    public $upload_location;
    public function __construct()
    {
        parent::__construct();
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }

        //Load models
        $this->upload_path = realpath(APPPATH . '../assets/uploads');
        $this->upload_location = base_url() . 'assets/uploads/';
        $this->load->model('action_model');
        $this->load->model('group_model');
        $this->load->model('actioncard_model');
    }

    public function index()
    {
        $params = $_SERVER['QUERY_STRING'];
        if ($params != null || $params != "") 
        {
            $validation_token = str_replace('validationToken=', '', $params);
            echo ($validation_token);
        } 
        else 
        {
            $json_string = file_get_contents("php://input");

            $this->handle_event_submitted($json_string);
        }

    }

    private function handle_event_submitted($json_string)
    {
        $json_object = json_decode($json_string);

        if (is_array(json_decode($json_string, true))) 
        {
            $event_type = $json_object->eventType;

            //fetch group from DB
            $group_unique_id = $json_object->objectId;
            $group_details = $this->group_model->get_group_details($group_unique_id);

            $group_name = $group_details[0]->group_name;
            $group_id = $group_details[0]->group_id;
            $action_card_unique_id = $json_object->data->actionId;
            $actionPackageId = $json_object->data->actionPackageId;
            $action_package_name = null;

            $existed_action_id = null;

            if ((strpos($event_type, 'Response') != false) || (strpos($event_type, 'response') != false)) 
            {
                $if_action_exists = $this->actioncard_model->check_if_action_exists($actionPackageId);

                if ($if_action_exists == "not_exist") 
                {
                    $to_do = 'save';
                } 
                else 
                {
                    $to_do = 'get_package_name';
                    $existed_action_id = $if_action_exists[0]->action_card_id;
                }

            } 
            else if (strpos($event_type, 'Created') != false || strpos($event_type, 'Created') != false) 
            {
                $to_do = 'created';
            }
            else if($event_type == 'MemberAdded')
            {
                $to_do = 'memberAdded';
            }


            if($to_do == 'memberAdded')
            {
                $this->handle_member_added($json_object);
            }
            else
            {
                $db_result = $this->actioncard_model->save_action_card($json_object, $group_name, $to_do, $existed_action_id);

                if ($to_do == 'save' || $to_do == 'created') {
                    $action_card_id = $db_result;
                } else if ($db_result == null) {
                    $action_card_id = $if_action_exists[0]->action_card_id;
                    $action_package_name = $if_action_exists[0]->action_card_package_name;
                }
                
                // Save to group_action_cards table
                if($action_card_id != false && ($to_do == 'save' || $to_do == 'created')){
                    $group_action_card_id = $this->group_model->save_group_action_cards(array(
                        'action_card_id' => $action_card_id,
                        'group_id' => $group_id
                    ));
                }

                if (($action_card_id != false)) 
                {
                    $response_with_questions = $json_object->data->responseDetails->responseWithQuestions;
                    $response_id = $json_object->data->responseId;
                    $event_id = $json_object->eventId;

                    foreach ($response_with_questions as $key => $response_with_question) 
                    {
                        $action_response_question_id = $this->action_model->save_action_response_question($response_with_question, $json_object, $action_card_id, $response_id, $event_id, $action_package_name, $this->upload_path, $this->upload_location);
                    }

                    echo "ActionResponse";
                } 
                else 
                {
                    echo "ActionCreated";
                }
            }

        } 
        else 
        {
            echo "No body was found";
        }
    }

    private function handle_member_added($json_object)
    {
        if(array_key_exists('profilePic', $json_object))
        {
            $profilePic = $json_object->profilePic;
        }
        else
        {
            $profilePic = "no profilePic";
        }

        $arr_members = array();

        array_push($arr_members, 
            array(
                'user_unique_id' => $json_object->data->memberId,
                'group_unique_id' => $json_object->objectId,
                'user_role' => $json_object->type,
                'user_mobile_number' => $json_object->member,
                'user_name' => $json_object->memberName,
                'user_profile_pic' => $profilePic,
                'user_is_provisioned' => 1,
            )
        );

        if ($this->group_model->save_group_members($arr_members)) 
        {
            echo 'memberAdded';
        }
        else
        {
            echo 'unable to aad member';
        }
    }

}