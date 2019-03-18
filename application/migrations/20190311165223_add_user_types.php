<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_user_types extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'user_type_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'user_type_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,

            ),
            'deleted' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => false,
                'default' => '0',

            ),
            'deleted_on' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'deleted_by' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
                'default' => '0',

            ),
            'modified_by' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => false,
                'default' => '0',
            ),
            'modified_on' => array(
                'type' => 'TIMESTAMP',
                'null' => false,
                'onupdate' => 'CURRENT_TIMESTAMP',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => false,
                'default' => '0',
            ),
        ));

        $this->dbforge->add_key('user_type_id', true);
        $this->dbforge->create_table('user_types');
        $data = array(
            'user_type_name' => 'admin',
        );
        $data = array(
            'user_type_name' => 'admin',
        );

        $this->db->insert('user_types', $data);

        $data = array(
            'user_type_name' => 'member',
        );

        $this->db->insert('user_types', $data);

        $data = array(
            'user_type_name' => 'subscriber',
        );

        $this->db->insert('user_types', $data);
    }
    public function down()
    {
        $this->dbforge->drop_table('user_types');
    }
}
