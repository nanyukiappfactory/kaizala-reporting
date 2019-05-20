<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_group_action_cards extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'group_action_card_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'action_card_id' => array(
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

        $this->dbforge->add_key('group_action_card_id', true);
        $this->dbforge->create_table('group_action_cards');
    }
    public function down()
    {
        $this->dbforge->drop_table('group_action_cards');
    }
}
