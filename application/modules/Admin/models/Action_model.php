<?php

class Action_model extends CI_Model
{
    public function save_action_response_question($response_with_question, $json_object, $action_card_id, $group_details, $response_id, $event_id, $action_card_name = NULL)
    {
        //Location
        $latitude = '';
        $longitude = '';
        $location = '';

        //Group name and group type
        $group_name = $group_details[0]->group_name;
        $group_type = $group_details[0]->group_type;

        //other details
        $action_card_unique_id = $json_object->data->actionId;
        $group_unique_id = $json_object->data->groupId;
        $responder_phone = $json_object->data->responder;
        $responder_name = $json_object->data->responderName;
        $responder_id = $json_object->data->responseId;
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
        else 
        {
            $action_answer = $response_with_question->answer;
        }

        $data = array(
            'action_unique_id' => $action_card_unique_id,
            'action_id' => $action_card_id,
            'group_unique_id' => $group_unique_id,
            'group_name' => $group_name,
            'group_type' => $group_type,
            'response_id' => $response_id,
            'event_id' => $event_id,
            'action_package' => $action_package,
            'package_id' => $package_id,
            'responder_id' => $responder_id,
            'responder_phone' => $responder_phone,
            'responder_name' => $responder_name,
            'action_question' => $action_question,
            'action_answer' => $action_answer,
            'action_question_type' => $action_question_type,
            'response_latitude' => $latitude,
            'response_longitude' => $longitude,
            'response_location' => $location,
            'created_at' => date('Y/m/d H:i:s'),
        );
        // echo json_encode($data);die();
        $this->db->insert('action_responses', $data);
        $action_response_question_id = $this->db->insert_id();

        if ($action_response_question_id) 
        {
            return $action_response_question_id;
        } 
        else 
        {
            return false;
        }
    }

    
    public function count_response($action_id)
    {
        $this->db->select('response_id');
        $this->db->distinct('response_id');
        $this->db->where('action_id', $action_id);
        $query = $this->db->get("action_responses");
        
        return $query->num_rows();
    }

    public function edit_package_name($action_id)
    {
        $package_name = $this->input->post('new_package_name');

        $card_data = array(
            'action_card_package' => $package_name,
        );

        $this->db->set($card_data);
        $this->db->where('action_card_id', $action_id);

        if ($this->db->update('action_cards')) {
            $response_data = array(
                'action_package' => $package_name,
            );
            $this->db->set($response_data);
            $this->db->where('action_id', $action_id);

			if ($this->db->update('action_responses')) 
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
        $this->db->select('*');
        $this->db->where('action_id', $action_id);
        $this->db->order_by($order, $order_method);

        return $this->db->get('action_responses')->result();
    }

}