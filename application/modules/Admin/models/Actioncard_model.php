<?php

class Actioncard_model extends CI_Model
{
    public function save_action_card($json_object, $group_name, $control, $action_card_id = NULL)
    {
        if($control == 'save' || $control == 'created')
        {
            $action_card_unique_id = $json_object->data->actionId;

            $action_package_id = $control == 'save' ? (array_key_exists('actionPackageId', $json_object->data) ? $json_object->data->actionPackageId : $json_object->data->actionId) : $json_object->data->actionId;
            $group_unique_id = $json_object->data->groupId;
            $action_card_subscription_id = $json_object->subscriptionId;
            $action_card_object_id = $json_object->objectId;
            $action_card_responder_id = $control == 'save' ? $json_object->data->responseId : 'null';
            $action_card_responder_phone = $control == 'save' ? $json_object->data->responder : 'null';
            $action_card_responder_name = $control == 'save' ? $json_object->data->responderName : 'null';
            $action_card_event_type = $json_object->eventType;

            $data = array(
                'action_card_unique_id' => $action_card_unique_id,
                'action_card_package' => $action_package_id,
                'group_unique_id' => $group_unique_id,
                'group_name' => $group_name,
                'action_card_subscription_id' => $action_card_subscription_id,
                'action_card_object_id' => $action_card_object_id,
                'action_card_responder_id' => $action_card_responder_id,
                'action_card_responder_phone' => $action_card_responder_phone,
                'action_card_responder_name' => $action_card_responder_name,
                'action_card_event_type' => $action_card_event_type,
                'created_at' => date('Y/m/d H:i:s'),
                'created_by' => 0,
            );

            $this->db->insert('action_cards', $data);
            $action_id = $this->db->insert_id();

            if ($action_id) {
                return $action_id;
            } else {
                return false;
            }
        }
        else if($control == 'update')
        {
            $action_card_responder_id = $json_object->data->responseId;
            $action_card_responder_phone = $json_object->data->responder;
            $action_card_responder_name = $json_object->data->responderName;

            $data = array(
                'action_card_responder_id' => $action_card_responder_id,
                'action_card_responder_phone' => $action_card_responder_phone,
                'action_card_responder_name' => $action_card_responder_name,
                'created_at' => date('Y/m/d H:i:s'),
            );

            $this->db->set($data);
            $this->db->where('action_card_id', $action_card_id);
            if ($this->db->update('action_cards')) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
    }

    public function check_if_action_exists($action_card_unique_id)
    {
        $this->db->select('action_card_id, action_card_package');
        $this->db->where('action_card_unique_id', $action_card_unique_id);
        $query = $this->db->get('action_cards');

        if ($query->num_rows() > 0) {
            return ($query->row());
        } else {
            return false;
        }

    }


    public function get_action_cards($order, $order_method)
    {
        $this->db->select('*');
        
        $this->db->order_by($order, $order_method);

        return $this->db->get('action_cards')->result();
    }

    public function all_action_cards()
    {
        return $this->db->get('action_cards')->result();
    }

    public function actions_count($where)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get("action_cards");

        return $query->num_rows();
    }

    public function get_action_package($where)
    {
        $this->db->select('action_card_package_id');
        $this->db->where($where);
        
        return $this->db->get('action_cards')->result();
    }

}