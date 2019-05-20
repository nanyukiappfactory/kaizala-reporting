<?php

class Group_model extends CI_Model
{
    public function get_groups($limit, $start, $order, $order_method, $where)
    {
        if ($where != null) {
            $this->db->where($where);
        }

        $this->db->limit($limit, $start);
        $this->db->order_by($order, $order_method);

        return $this->db->get("groups");
    }

    public function get_webhook_groups($where)
    {
        $order = 'group_name';
        $order_method = 'ASC';
        if ($where == null) {
            $where = "webhook_id IS NOT NULL OR webhook_id != 'null'";
        }

        $this->db->where($where);
        $this->db->order_by($order, $order_method);

        return $this->db->get("groups");
    }

    public function groups_count($where)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get("groups");

        return $query->num_rows();
    }

    public function get_group_users($group_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('group_users', 'users.user_id = group_users.user_id');
        $this->db->where('group_users.group_id', $group_id);

        return $this->db->get();
    }

    public function save_members($member)
    {
        $this->db->insert('users', $member);
        $user_id = $this->db->insert_id();
        if ($user_id) {
            return $user_id;
        } else {
            return false;
        }
    }

    public function user_exist($user_unique_id)
    {
        $where = array(
            'user_unique_id' => $user_unique_id
        );
        $this->db->select('user_id');
        $this->db->where($where);
        $query = $this->db->get('users');

        if($query->num_rows() > 0){
            $user = $query->result();
            return $query->result()[0]->user_id;
        }
        else{
            return false;
        }
    }

    public function save_group_users($group_users)
    {
        if($this->db->insert('group_users', $group_users)){
            return true;
        }
        else{
            return false;
        }
    }

    private function group_exists($group_unique_id)
    {
        $this->db->where('group_unique_id', $group_unique_id);
        $query = $this->db->get('groups');

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function save_group($groups)
    {
        $count = 0;
        foreach ($groups as $key => $group) {
            $group_name = $group->groupName;
            $group_unique_id = $group->groupId;
            $group_image_url = $group->groupImageUrl;
            $group_type = $group->groupType;
            $has_sub_groups = $group->hasSubGroups;
            $has_parent_groups = $group->hasParentGroups;

            if ($this->group_exists($group_unique_id) == false) {
                $count++;
                $data = array(
                    'group_name' => $group_name,
                    'group_unique_id' => $group_unique_id,
                    'group_image_url' => $group_image_url,
                    'group_type' => $group_type,
                    'has_sub_groups' => $has_sub_groups,
                    'has_parent_groups' => $has_parent_groups,
                    'created_at' => date('Y/m/d H:i:s'),
                    'created_by' => 0,
                );

                if ($this->db->insert('groups', $data) == false) {
                    return 'FAILED';
                }
            }
        }

        return $count;
    }

    public function get_group_unique_id($group_id)
    {
        $this->db->select('group_unique_id');
        $this->db->where('group_id = ' . $group_id);
        $query = $this->db->get('groups')->row();

        return $query;
    }

    public function get_webhook_id($group_id)
    {
        $this->db->select('webhook_id');
        $this->db->where('group_id = ' . $group_id);
        $query = $this->db->get('groups')->row();

        return $query;
    }

    public function save_group_action_cards($group_action_card_details)
    {
        $this->db->insert('group_action_cards', $group_action_card_details);
        $group_action_card_id = $this->db->insert_id();

        if ($group_action_card_id) {
            return $group_action_card_id;
        } else {
            return false;
        }
        
    }

    public function get_group_details($group_unique_id)
    {
        $where = "group_unique_id = '" . $group_unique_id . "'";
        $this->db->select('group_id, group_name, group_type');
        $this->db->where($where);
        $query = $this->db->get('groups')->result();

        return $query;
    }

    public function activate_group($group_id, $webhook_id)
    {
        $data = array(
            "webhook_id" => $webhook_id,
            "group_status" => 1,
        );

        $this->db->set($data);
        $this->db->where('group_id = ' . $group_id);
        if ($this->db->update('groups')) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivate_group($group_id)
    {
        $data = array(
            "webhook_id" => "null",
            "group_status" => 0,
        );

        $this->db->set($data);
        $this->db->where('group_id = ' . $group_id);
        if ($this->db->update('groups')) {
            return true;
        } else {
            return false;
        }
    }
}
