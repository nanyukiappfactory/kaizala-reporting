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

    public function groups_count($where)
    {
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get("groups");

        return $query->num_rows();
    }

    public function get_group_users($where)
    {
        $this->db->where($where);
        return $this->db->get('users');
    }

    public function save_group_members($members)
    {
        if ($this->db->insert_batch('users', $members)) {
            return true;
        } else {
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

    public function get_group_details($group_unique_id)
    {
        $where = "group_unique_id = '" . $group_unique_id . "'";
        $this->db->select('group_name, group_type');
        $this->db->where($where);
        $query = $this->db->get('groups')->result();

        return $query;
    }

    public function activate_group($group_id, $webhook_id)
    {
        $data = array(
            "webhook_id" => $webhook_id,
            "group_status" => 1
        );

        $this->db->set($data);
        $this->db->where('group_id = ' . $group_id);
        if($this->db->update('groups'))
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }

    public function deactivate_group($group_id)
    {
        $data = array(
            "webhook_id" => "null",
            "group_status" => 0
        );

        $this->db->set($data);
        $this->db->where('group_id = ' . $group_id);
        if($this->db->update('groups'))
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
}
