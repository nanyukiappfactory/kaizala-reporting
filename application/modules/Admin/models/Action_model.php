<?php

class Action_model extends CI_Model
{
    public function save_action_response_question($response_with_question, $json_object, $action_card_id,  $response_id, $event_id, $image_url, $upload_location, $action_card_name = NULL)
    {
        //Location
        $latitude = '';
        $longitude = '';
        $location = '';

        //other details
        $action_card_unique_id = $json_object->data->actionId;
        $group_unique_id = $json_object->data->groupId;
        $responder_phone = $json_object->data->responder;
        $responder_name = $json_object->data->responderName;
        $responder_id = $json_object->data->responderId;
        $response_id = $json_object->data->responseId;

        if(array_key_exists('actionPackageId', $json_object->data) || array_key_exists('packageId', $json_object->data))
        {
            $package_id = $json_object->data->packageId;
            $action_package = $json_object->data->actionPackageId;
        }
        else
        {
            $package_id = $json_object->data->actionId;
            $action_package = $json_object->data->actionId;
        }

        $action_question = $response_with_question->title;
        $action_question_type = $response_with_question->type;

        if ($action_question_type == "MultipleOption" || $action_question_type == "MultiOption") 
        {
            $action_answer = json_encode($response_with_question->answer);
        } 
        else if ($action_question_type == "SingleOption") 
        {
            $answer = $response_with_question->answer;
            $action_answer = $answer[0];
        } 
        else if ($action_question_type == "Location") 
        {
            $loc = $response_with_question->answer;
            $latitude = array_key_exists('lt', $loc) ? $loc->lt : '';
            $longitude = array_key_exists('lg', $loc) ? $loc->lg : '';
            $location = array_key_exists('n', $loc) ? $loc->n : '';
            $action_answer = json_encode($loc);
        } 
        else if ($action_question_type == "DateTime") 
        {
            $str_date = $response_with_question->answer;
            $num_date = $str_date + 0;
            $date = intval($num_date / 1000);
            $action_answer = date("Y-m-d H:i:s", $date);
        } 
        else 
        {
            $action_answer = $response_with_question->answer;
        }

        $action_card_question_data = array(
            'action_card_id' => $action_card_id,
            'action_card_question' => $action_question,
            'action_card_question_type' => $action_question_type,
            'created_at' => date('Y-m-d H:i:s'),
        );

        // Save responses
        $action_card_question_id = $this->save_response_question($action_card_question_data, 'action_card_questions');

        if($action_card_question_id)
        {
            if ($action_question_type == "AttachmentList" || $action_question_type == "Image") 
            {
                $images_obj = $response_with_question->answer;
                foreach ($images_obj as $key => $image) {
                    $action_answer = $this->save_image($image->mediaUrl, $image_url, $upload_location);
                    $data = array(
                        'action_card_response_unique_id' => $action_card_unique_id,
                        'action_card_question_id' => $action_card_question_id,
                        'group_unique_id' => $group_unique_id,
                        'user_unique_id' => $responder_id,
                        'responder_name' => $responder_name,
                        'responder_phone' => $responder_phone,
                        'unique_response_id' => $response_id,
                        'action_card_question_location' => $location,
                        'action_card_question_latitude' => $latitude,
                        'action_card_question_longitude' => $longitude,
                        'action_card_package_id' => $action_package,
                        'event_id' => $event_id,
                        'action_answer' => $action_answer,
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $action_response_question_id = $this->save_response_question($data, 'action_card_responses');
                }
            } 
            else{
                $data = array(
                    'action_card_response_unique_id' => $action_card_unique_id,
                    'action_card_question_id' => $action_card_question_id,
                    'group_unique_id' => $group_unique_id,
                    'user_unique_id' => $responder_id,
                    'responder_name' => $responder_name,
                    'responder_phone' => $responder_phone,
                    'unique_response_id' => $response_id,
                    'action_card_question_location' => $location,
                    'action_card_question_latitude' => $latitude,
                    'action_card_question_longitude' => $longitude,
                    'action_card_package_id' => $action_package,
                    'event_id' => $event_id,
                    'action_answer' => $action_answer,
                    'created_at' => date('Y-m-d H:i:s'),
                );
            $action_response_question_id = $this->save_response_question($data, 'action_card_responses');
            }

            return $action_response_question_id;
        }
    }

    private function save_response_question($data, $table)
    {
        $this->db->insert($table, $data);
        $last_inserted_id = $this->db->insert_id();

        if ($last_inserted_id) 
        {
            return $last_inserted_id;
        } 
        else 
        {
            return false;
        }
    }
    
    public function count_response($action_id)
    {
        $this->db->select('unique_response_id');
        $this->db->distinct('unique_response_id');
        $this->db->where('action_card_response_id', $action_id);
        $query = $this->db->get("action_card_responses");
        
        return $query->num_rows();
    }

    public function edit_package_name($action_id)
    {
        $package_name = $this->input->post('new_package_name');

        $card_data = array(
            'action_card_package_id' => $package_name,
        );

        $this->db->set($card_data);
        $this->db->where('action_card_response_id', $action_id);

        if ($this->db->update('action_cards')) {
            $response_data = array(
                'action_card_package_id' => $package_name,
            );
            $this->db->set($response_data);
            $this->db->where('action_card_response_id', $action_id);

			if ($this->db->update('action_card_responses')) 
			{
                return true;
			} 
			else 
			{
                return false;
            }
		} 
		else 
		{
            return false;
        }
    }

    
    public function action_responses_count($where)
    {
        $this->db->where($where);
        $query = $this->db->get("action_responses");
        return $query->num_rows();
    }

    public function get_responses($order, $order_method, $action_id)
    {
        $this->db->select('action_card_questions.action_card_question, action_card_questions.action_card_question_type, action_card_responses.*, groups.group_name');
        $this->db->from('action_card_questions');
        $this->db->join('action_card_responses', 'action_card_questions.action_card_question_id = action_card_responses.action_card_question_id');
        $this->db->join('groups', 'action_card_responses.group_unique_id = groups.group_unique_id');
        $this->db->where('action_card_id', $action_id);
        $this->db->order_by($order, $order_method);

        $result = $this->db->get()->result();
        // echo json_encode($result);die();
        return $result;
    }

    private function save_image($image_url, $path, $upload_location)
    {
        $image_name = md5(date("Y-m-d H:i:s"));
        $content = file_get_contents($image_url);
        file_put_contents($path . '/' . $image_name . '.jpg', $content);

        return $upload_location . '/' . $image_name . '.jpg';
    }

}