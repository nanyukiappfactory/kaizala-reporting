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
            $json_string = '{
                "subscriptionId": "7219d2cc-2016-4684-85f0-5efd5b47b5d5",
                "objectId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                "objectType": "Group",
                "eventType": "ActionResponse",
                "eventId": "4207916c-ede0-4bf7-b7b1-10764e4dda88",
                "data": {
                  "actionId": "3f01fbcb-d4bf-46e3-9a71-e39a58d79164",
                  "actionPackageId": "com.dynamic.appf",
                  "packageId": "com.dynamic.appf.2",
                  "groupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                  "sourceGroupId": "29b9bf47-9249-4808-a5ec-97fa940a29f7",
                  "responseId": "4207916c-ede0-4bf7-b7b1-10764e4dda88",
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
                        "title": "Business Location",
                        "type": "Text",
                        "options": [],
                        "answer": "Hahaha "
                      },
                      {
                        "title": "Business Name",
                        "type": "Text",
                        "options": [],
                        "answer": "Test"
                      },
                      {
                        "title": "Category",
                        "type": "SingleOption",
                        "options": [
                          {
                            "title": "Banks"
                          },
                          {
                            "title": "Churches"
                          },
                          {
                            "title": "Service"
                          },
                          {
                            "title": "Hotels"
                          },
                          {
                            "title": "Restaurants"
                          }
                        ],
                        "answer": [
                          "Banks"
                        ]
                      },
                      {
                        "title": "Phone Number",
                        "type": "Text",
                        "options": [],
                        "answer": "07828282"
                      },
                      {
                        "title": "Business Image",
                        "type": "AttachmentList",
                        "options": [],
                        "answer": [
                          {
                            "mediaUrl": "https://cdn.inc-000.kms.osi.office.net/att/a37bf5b96d3885a2ee99bd6e61ff0cb2619ef566e41f7fd4f48525c4225dd2fa.jpg?sv=2015-12-11&sr=b&sig=qJDTs8t%2BaOqsZwIoP%2BxAO7pP4bE7YvOijigNEtxNSzI%3D&st=2019-05-24T05:56:42Z&se=2293-03-08T06:56:42Z&sp=r&rscd=attachment%3Bfilename%3D&mdId=EABmzpBb5MAa/zey/z+cyrga5gWjTVZ/Q0M3Vlo56JefkvI9fKSN3MpsJxAxNz9mHCbNv7khp1GiYj94+LnAHlf7myEXKwHNOoNFkbD0Bx9te7g2ckViQEbnIg9qUllvAInacc293BWB9sJpQSmw4CXiRY8dDW2Jln/bBXLfFiai31AS1N0AZKZ6fh4mHHnS5B7CxaHjwsW1uGJogli4jidS53xhySdGbPlVjVl0HGHyo42gJzO0A8ZUD+cApd/XiUH+Nqt4wAoowLBfb8iH12gX15JgVIp5YbHFlgs4QZWNuQN0aG3oLYsa/xuGzuE+dWit8BSh5Szc2qtn4Fj/u2k7",
                            "mediaFileName": "IMG_19-05-24_095620206_1.jpg"
                          },
                          {
                            "mediaUrl": "https://cdn.inc-000.kms.osi.office.net/att/6ddd3745359dd1a170cb98e5416e87bfc3edef1c747eb6dda2f7d934d2639071.jpg?sv=2015-12-11&sr=b&sig=y0u23CmAyZdyBRcYbGe8YCc8QVJmO97m1GVGYwpenYU%3D&st=2019-05-24T05:56:43Z&se=2293-03-08T06:56:43Z&sp=r&rscd=attachment%3Bfilename%3D&mdId=EACXw9lro7aaZ38YF+MbmTrSpw3AQRcwdj//UvZ5io71DB5D0UonMDfbbnakVSADCkiy0Ezt2VOv/PZhTOQ81b3lZwk0RJRPyMhi0oB5aCveMrKSNVFt+TqSU4faDH9oo20RQ8QXgupfEq7jvTnI8r/a7+7hSxvZE3hZWPZDEhKMgrkLE7zw3r7PeR7zQ1GwhLVfCjT/+EtUjVc+KRwcDo3adGSRsx4V1zT3kt8wlQtWyZDIVsTk/XMPx3kl4/X33IHCS/JARznv8q+bQxDj82dhk57RP+wj96mHbJpj0P667la7aTw2kz3w1qFydZZRbiOcxyaTvaBtiQNMjCq+oCyM",
                            "mediaFileName": "IMG_19-05-24_095620319_2.jpg"
                          },
                          {
                            "mediaUrl": "https://cdn.inc-000.kms.osi.office.net/att/1516f32a86adcec4501c4261d3d3a5a8a4ed746a3c06a046ca96b8e3964bc240.jpg?sv=2015-12-11&sr=b&sig=RgmPvZevjcWaRlFSQV6tzS0YD2c%2FMu2MT9bNkQwEKTs%3D&st=2019-05-24T05:56:43Z&se=2293-03-08T06:56:43Z&sp=r&rscd=attachment%3Bfilename%3D&mdId=EAAkbH4IFbUw2FNmelzmOdsG0kpF2bbHZ4CEZFOoy/44EE5G3BTBCeqr522p3MRizmgz4z1k384889m7W9dMakSMrEqxHUPG1XdmWyCSq0nio3JLfPJZylpbGt+znMK2xOboQCirhLQbbCQsW5rgzLh3SeaFO11/HHlO/ekQ2N/xI0Kv6LIZGJZ08K64OvHRXwlLLOWL1CBzYZqR1UQnal91DtlJL4EqdaLuQHDGlzh4R3PR6UNBY2L6Zqj7qIvrmE89mLCPOij5tSsJUq6Fa/c7In7uFoimoKMbXFFhGqhd2+gAf1qGJuAYlmxdN/2/V0AG+73Ank5nVKruKX782AhJ",
                            "mediaFileName": "IMG_19-05-24_095620487_3.jpg"
                          }
                        ]
                      },
                      {
                        "title": "Name",
                        "type": "Text",
                        "options": [],
                        "answer": "Samuel Wanjohi"
                      },
                      {
                        "title": "Phone",
                        "type": "Text",
                        "options": [],
                        "answer": "+254710967675"
                      },
                      {
                        "title": "Location",
                        "type": "Location",
                        "options": [],
                        "answer": {
                          "lt": 0.0183318,
                          "lg": 37.0741805,
                          "n": "0.0183318, 37.0741805",
                          "acc": 14.284000396728516
                        }
                      },
                      {
                        "title": "Response Time",
                        "type": "DateTime",
                        "options": [],
                        "answer": 1558681000175
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
                // echo $action_package_name;die();

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