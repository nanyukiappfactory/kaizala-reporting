<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Migration_Add_groups extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(

            'group_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false,

            ),
            'group_unique_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false,

            ),
            'group_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,

            ),
            'group_image_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ),
            'group_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => false,
            ),
            'has_sub_groups' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ),
            'has_parent_groups' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true,
            ),
            'webhook_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ),
            'group_status' => array(
                'type' => 'TINYINT',
                'constraint' => '2',
                'null' => true,
                'default' => '0',
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

        $this->dbforge->add_key('group_id', true);
        $this->dbforge->create_table('groups');
    }
    public function down()
    {
        $this->dbforge->drop_table('groups');
    }
}
