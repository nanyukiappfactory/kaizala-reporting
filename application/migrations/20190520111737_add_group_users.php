<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_group_users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'group_user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'group_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            )
        ));

        $this->dbforge->add_key('group_user_id', true);
        $this->dbforge->create_table('group_users');
    }
    public function down()
    {
        $this->dbforge->drop_table('group_users');
    }
}
