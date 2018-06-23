<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_base extends CI_Migration {

  public function up() {

    ## Create Table migrations
    $this->dbforge->add_field([
      'version' => [
        'type'       => 'bigint',
        'constraint' => '20'
      ]
    ]);
    $this->dbforge->create_table('migrations', true);
    $this->db->query('ALTER TABLE  `' . $this->db->dbprefix . 'migrations` ENGINE = MyISAM');

    ## Create Table sessions
    $this->dbforge->add_field([
      'session_id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'unsigned'       => true,
        'auto_increment' => true,
        'unique'         => true
      ],
      'session_user_id' => [
        'type'       => 'int',
        'constraint' => '11'
      ],
      'session_key' => [
        'type'       => 'varchar',
        'constraint' => '254'
      ],
      'session_date_created' => [
        'type' => 'datetime',
      ],
      'session_last_used' => [
        'type' => 'datetime',
      ]
    ]);
    $this->dbforge->add_key('session_id', true);
    $this->dbforge->add_key('session_user_id');
    $this->dbforge->add_key('session_key');
    $this->dbforge->create_table('sessions', true);
    $this->db->query('ALTER TABLE  `' . $this->db->dbprefix . 'sessions` ENGINE = InnoDB');

    ## Create Table settings
    $this->dbforge->add_field([
      'setting_id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'unsigned'       => true,
        'auto_increment' => true,
        'unique'         => true
      ],
      'setting_code' => [
        'type'       => 'varchar',
        'constraint' => '255',
        'unique'     => true
      ],
      'setting_value' => [
        'type' => 'text'
      ],
      'setting_changed_date' => [
        'type' => 'datetime',
        'null' => true
      ],
      'setting_changed_by' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true
      ]
    ]);
    $this->dbforge->add_key('setting_id', true);
    $this->dbforge->add_key('setting_code', true);
    $this->dbforge->create_table('settings', true);
    $this->db->query('ALTER TABLE `'. $this->db->dbprefix . 'settings` ENGINE = InnoDB');

    # seed the settings db
    $this->db->query('INSERT INTO `' . $this->db->dbprefix . 'settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("1", "application_url_auto", "on", null, null, null)');
    $this->db->query('INSERT INTO `' . $this->db->dbprefix . 'settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("2", "application_url", "", null, null, null)');
    $this->db->query('INSERT INTO `' . $this->db->dbprefix . 'settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("3", "application_title", "Smart Monitoring", null, null, null)');
    $this->db->query('INSERT INTO `' . $this->db->dbprefix . 'settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("8", "application_logo", "", null, null, null)');

    ## Create Table users
    $this->dbforge->add_field([
      'user_id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'unsigned'       => true,
        'auto_increment' => true,
        'unique'         => true
      ],
      'user_name' => [
        'type'       => 'varchar',
        'constraint' => '50',
        'unique'     => true
      ],
      'user_mail' => [
        'type'       => 'varchar',
        'constraint' => '254'
      ],
      'user_full_name' => [
        'type'       => 'varchar',
        'constraint' => '255'
      ],
      'user_avatar' => [
        'type'       => 'varchar',
        'constraint' => '255',
        'null'       => true
      ],
      'user_password' => [
        'type'       => 'varchar',
        'constraint' => '254'
      ],
      'user_created_date' => [
        'type' => 'datetime'
      ],
      'user_created_by' => [
        'type'       => 'int',
        'constraint' => '11'
      ],
      'user_last_change_date' => [
        'type' => 'datetime',
        'null' => true
      ],
      'user_last_change_by' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true
      ],
      'user_last_change_audit_id' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true
      ],
      'user_role' => [
        'type'    => 'enum("admin","manager")',
        'default' => 'manager'
      ],
      'user_status' => [
        'type'       => 'tinyint',
        'constraint' => '1',
        'default'    => 1
      ],
      'user_removed' => [
        'type'       => 'tinyint',
        'constraint' => '1',
        'default'    => 0
      ],
      'user_removed_date' => [
        'type' => 'datetime',
        'null' => true
      ]
    ]);
    $this->dbforge->add_key('user_id', true);
    $this->dbforge->add_key('user_name', true);
    $this->dbforge->add_key('user_mail');
    $this->dbforge->create_table('users', true);
    $this->db->query('ALTER TABLE  `' . $this->db->dbprefix . 'users` ENGINE = InnoDB');

    $this->db->query('INSERT INTO `' . $this->db->dbprefix . 'users` (`user_id`, `user_name`, `user_mail`, `user_full_name`, `user_password`, `user_created_date`, `user_created_by`, `user_last_change_date`, `user_last_change_by`, `user_last_change_audit_id`, `user_role`, `user_status`, `user_removed`, `user_removed_date`)
    VALUES(NULL, "admin", "sabyn_george@yahoo.com", "Admin", "7838bd3ee3cc110a6dc8b49567dfcfebd94e2237", "'.date('Y-m-d H:i:s').'", "0", NULL, NULL, NULL, "admin", "1", "0", NULL)');
  }

  public function down()	{

    ### Drop table audit ##
    $this->dbforge->drop_table('audit', true);

    ### Drop table migrations ##
    $this->dbforge->drop_table('migrations', true);

    ### Drop table sessions ##
    $this->dbforge->drop_table('sessions', true);

    ### Drop table settings ##
    $this->dbforge->drop_table('settings', true);

    ### Drop table users ##
    $this->dbforge->drop_table('users', true);

  }
}
