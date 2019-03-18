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
            'action_card_package' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_event_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'group_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,

            ),
            'group_name' => array(
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
            'action_card_responder_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_responder_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ),
            'action_card_responder_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '60',
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

        $this->dbforge->add_key('action_card_id', true);
        $this->dbforge->create_table('action_cards');
    }
    public function down()
    {
        $this->dbforge->drop_table('action_cards');
    }
}
