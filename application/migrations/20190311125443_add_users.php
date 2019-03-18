<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'user_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false,

            ),
            'group_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false,

            ),
            'user_role' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false,

            ),
            'user_mobile_number' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
                'default' => 'null',
            ),
            'user_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'default' => 'null',
            ),
            'user_profile_pic' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'user_is_provisioned' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
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

        $this->dbforge->add_key('user_id', true);
        $this->dbforge->create_table('users');
    }
    public function down()
    {
        $this->dbforge->drop_table('users');
    }
}
