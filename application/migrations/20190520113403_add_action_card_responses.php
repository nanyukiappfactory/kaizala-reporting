<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_action_card_responses extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'action_card_response_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,
            ),
            'action_card_response_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_question_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => false,
            ),
            'group_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'user_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'responder_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false,
            ),
            'responder_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ),
            'action_answer' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ), 
            'action_card_question_location' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'action_card_question_latitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'action_card_question_longitude' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'action_card_package_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'event_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'unique_response_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            )
        ));

        $this->dbforge->add_key('action_card_response_id', true);
        $this->dbforge->create_table('action_card_responses');
    }
    public function down()
    {
        $this->dbforge->drop_table('action_card_responses');
    }
}
