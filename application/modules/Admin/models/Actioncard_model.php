<?php

class Actioncard_model extends CI_Model
{
    public function save_action_card($json_object, $group_name, $control, $action_card_id = NULL)
    {
        if($control == 'save' || $control == 'created')
        {
            $action_card_unique_id = $json_object->data->actionId;
            $action_package_id = $control == 'save' ? (array_key_exists('actionPackageId', $json_object->data) ? $json_object->data->actionPackageId : $json_object->data->actionId) : $json_object->data->actionId;
            $action_card_version_id = $json_object->data->packageId;
            $action_card_subscription_id = $json_object->subscriptionId;
            $action_card_object_id = $json_object->objectId;
            $action_card_event_type = $json_object->eventType;

            $data = array(
                'action_card_unique_id' => $action_card_unique_id,
                'action_card_package_id' => $action_package_id,
                'action_card_subscription_id' => $action_card_subscription_id,
                'action_card_version_id' => $action_card_version_id,
                'action_card_object_id' => $action_card_object_id,
                'action_card_event_type' => $action_card_event_type,
                'created_at' => date('Y/m/d H:i:s')
            );

            $this->db->insert('action_cards', $data);
            $action_id = $this->db->insert_id();

            if ($action_id) {
                return $action_id;
            } else {
                return false;
            }
        }
    }

    public function check_if_action_exists($action_card_unique_id)
    {
        $this->db->select('action_card_id, action_card_package_id');
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
        $this->db->select('action_cards.*, groups.group_name');
        $this->db->from('action_cards');
        $this->db->join('group_action_cards', 'action_cards.action_card_id = group_action_cards.action_card_id');
        $this->db->join('groups', 'groups.group_id = group_action_cards.group_id');
        $this->db->order_by($order, $order_method);

        return $this->db->get()->result();
    }

    public function all_action_cards()
    {
        $this->db->select('action_cards.*, groups.group_name, groups.group_unique_id');
        $this->db->from('action_cards');
        $this->db->join('group_action_cards', 'action_cards.action_card_id = group_action_cards.action_card_id');
        $this->db->join('groups', 'groups.group_id = group_action_cards.group_id');

        return $this->db->get()->result();
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