<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_links extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 9,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'hash' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'unique' => true,
            ]
        ]);

        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('links');
    }

    public function down()
    {
        $this->dbforge->drop_table('links');
    }
}