<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Action extends CI_Controller
{
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
            // $json_string = file_get_contents("php://input");
            $json_string = '{
                "subscriptionId": "7219d2cc-2016-4684-85f0-5efd5b47b5d5",
                "objectId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                "objectType": "Group",
                "eventType": "ActionResponse",
                "eventId": "96e288e0-cf8a-4664-9e0e-fa687c29d70f",
                "data": {
                  "actionId": "01a98cf4-8872-4694-ad5b-f5390ce6bcd6",
                  "actionPackageId": "fff2ea20-dd0f-41fe-9010-eac11d28cf7c",
                  "packageId": "fff2ea20-dd0f-41fe-9010-eac11d28cf7c.2",
                  "groupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                  "sourceGroupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                  "responseId": "96e288e0-cf8a-4664-9e0e-fa687c29d70f",
                  "isUpdateResponse": false,
                  "responder": "+254710967675",
                  "responderId": "d8636972-32f6-47e1-91b6-53398e9403f6",
                  "creatorId": "00000000-0000-0000-0000-000000000000",
                  "responderName": "Samuel Wanjohi",
                  "responderProfilePic": "",
                  "isAnonymous": false,
                  "responseDetails": {
                    "responseWithQuestions": [
                      {
                        "title": "Keyword",
                        "type": "Text",
                        "options": [],
                        "answer": "Hahaha "
                      },
                      {
                        "title": "Location",
                        "type": "Text",
                        "options": [],
                        "answer": "Hotel "
                      },
                      {
                        "title": "State",
                        "type": "Text",
                        "options": [],
                        "answer": "Iwokek"
                      }
                    ]
                  },
                  "Properties": []
                },
                "fromUser": "+254710967675",
                "fromUserId": "d8636972-32f6-47e1-91b6-53398e9403f6",
                "isBotfromUser": false,
                "fromUserName": "Samuel Wanjohi",
                "fromUserProfilePic": "",
                "groupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                "sourceGroupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7"
              }';

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

            $existed_action_id = null;

            if ((strpos($event_type, 'Response') != false) || (strpos($event_type, 'response') != false)) 
            {
                $if_action_exists = $this->actioncard_model->check_if_action_exists($action_card_unique_id);

                if ($if_action_exists == false) 
                {
                    $to_do = 'save';
                } 
                else 
                {
                    $existed_action_id = $if_action_exists->action_card_id;

                    $to_do = 'update';
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
                } else if ($to_do == 'update') {
                    $action_card_id = $if_action_exists->action_card_id;
                }

                // Save to group_action_cards table
                if($action_card_id != false){
                    $group_action_card_id = $this->group_model->save_group_action_cards(array(
                        'action_card_id' => $action_card_id,
                        'group_id' => $group_id
                    ));
                }

                if (($action_card_id != false) && ($to_do != 'created')) 
                {
                    $response_with_questions = $json_object->data->responseDetails->responseWithQuestions;
                    $response_id = $json_object->data->responseId;
                    $event_id = $json_object->eventId;

                    foreach ($response_with_questions as $key => $response_with_question) 
                    {
                        $action_response_question_id = $this->action_model->save_action_response_question($response_with_question, $json_object, $action_card_id, $response_id, $event_id);
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