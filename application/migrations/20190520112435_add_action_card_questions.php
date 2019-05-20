<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_action_card_questions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'action_card_question_id' => array(
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
            'action_card_question' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ),
            'action_card_question_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
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
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => true,
            )
        ));

        $this->dbforge->add_key('action_card_question_id', true);
        $this->dbforge->create_table('action_card_questions');
    }
    public function down()
    {
        $this->dbforge->drop_table('action_card_questions');
    }
}
