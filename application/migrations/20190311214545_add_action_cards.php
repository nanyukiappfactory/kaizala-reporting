<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_action_cards extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'action_card_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'action_card_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,

            ),
            'action_card_package_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_package_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_version_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_event_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_subscription_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,

            ),
            'action_card_object_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ),
            'deleted' => array(
                'type' => 'TINYINT',
                'constraint' => '1',
                'null' => false,
                'default' => '0',

            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            )
        ));

        $this->dbforge->add_key('action_card_id', true);
        $this->dbforge->create_table('action_cards');
    }
    public function down()
    {
        $this->dbforge->drop_table('action_cards');
    }
}
