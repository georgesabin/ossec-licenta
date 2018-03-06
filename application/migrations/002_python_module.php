<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_python_module extends CI_Migration {

    public function up() {

        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => true
            ],
            'agent_id' => [
                'type' => 'int',
                'constraint' => '11',
                'unique' => true
            ],
            'agent_name' => [
                'type' => 'varchar',
                'constraint' => '50',
                'unique' => true
            ],
            'agent_ip' => [
                'type' => 'varchar',
                'constraint' => '33',
                'unique' => true
            ]
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('agent_id', true);
        $this->dbforge->create_table('agents', true);
        $this->db->query('ALTER TABLE  `' . $this->db->dbprefix . 'agents` ENGINE = InnoDB');

    }

}