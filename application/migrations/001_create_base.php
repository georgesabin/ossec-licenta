<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_base extends CI_Migration {

  public function up() {

    ## Create Table keys
    $this->dbforge->add_field([
      'id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'auto_increment' => true
      ],
      'user_id' => [
        'type'       => 'int',
        'constraint' => '11',
      ],
      'key' => [
        'type'       => 'varchar',
        'constraint' => '40',
      ],
      'level' => [
        'type'       => 'int',
        'constraint' => '2'
      ],
      'ignore_limits' => [
        'type'       => 'tinyint',
        'constraint' => '1',
        'default'    => 0
      ],
      'is_private_key' => [
        'type'       => 'tinyint',
        'constraint' => '1',
        'default'    => 0
      ],
      'ip_addresses' => [
        'type' => 'text',
        'null' => true
      ],
      'date_created' => [
        'type'       => 'int',
        'constraint' => '11'
      ]
    ]);
    $this->dbforge->add_key('id', true);
    $this->dbforge->create_table('keys', true);
    $this->db->query('ALTER TABLE `keys` ENGINE = InnoDB');

    ## Create Table smartbe_audit
    $this->dbforge->add_field([
      'log_id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'unsigned'       => true,
        'auto_increment' => true
      ],
      'log_action' => [
        'type'       => 'varchar',
        'constraint' => '255'
      ],
      'log_date' => [
        'type' => 'datetime',
      ],
      'log_user_id' => [
        'type'       => 'int',
        'constraint' => '11',
        'default'    => 0
      ],
      'log_target_entity_type' => [
        'type' => 'enum("rule_inbound","rule_outbound","did","user","none")',
      ],
      'log_target_entity_id' => [
        'type'       => 'int',
        'constraint' => '11'
      ],
      'log_value' => [
        'type' => 'text',
        'null' => true
      ],
    ]);
    $this->dbforge->add_key('log_id', true);
    $this->dbforge->add_key('log_id');
    $this->dbforge->add_key('log_action');
    $this->dbforge->create_table('smartbe_audit', true);
    $this->db->query('ALTER TABLE  `smartbe_audit` ENGINE = InnoDB');

    ## Create Table smartbe_keys
    $this->dbforge->add_field([
      'key_id' => [
        'type'           => 'int',
        'constraint'     => '11',
        'unsigned'       => true,
        'auto_increment' => true
      ],
      'key_value' => [
        'type'       => 'varchar',
        'constraint' => '40'
      ],
      'key_datetime_created' => [
        'type' => 'datetime',
      ],
      'key_datetime_last_used' => [
        'type' => 'datetime',
      ],
      'key_description' => [
        'type'       => 'varchar',
        'constraint' => '255'
      ],
      'key_status' => [
        'type'       => 'bit',
        'constraint' => '1',
        'default'    => 1
      ]
    ]);
    $this->dbforge->add_key('key_id', true);
    $this->dbforge->add_key('key_id');
    $this->dbforge->add_key('key_value', true);
    $this->dbforge->add_key('key_value');
    $this->dbforge->create_table('smartbe_keys', true);
    $this->db->query('ALTER TABLE  `smartbe_keys` ENGINE = InnoDB');

    ## Create Table smartbe_migrations
    //$this->dbforge->add_field('`version` bigint(20) NOT NULL ');
    $this->dbforge->add_field([
      'version' => [
        'type'       => 'bigint',
        'constraint' => '20'
      ]
    ]);
    $this->dbforge->create_table('smartbe_migrations', true);
    $this->db->query('ALTER TABLE  `smartbe_migrations` ENGINE = MyISAM');

    ## Create Table smartbe_sessions
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
    $this->dbforge->create_table('smartbe_sessions', true);
    $this->db->query('ALTER TABLE  `smartbe_sessions` ENGINE = InnoDB');

    ## Create Table smartbe_settings
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
      ],
      'setting_changed_audit_id' => [
        'type'       => 'int',
        'constraint' => '11',
        'null'       => true
      ]
    ]);
    $this->dbforge->add_key('setting_id', true);
    $this->dbforge->add_key('setting_code', true);
    $this->dbforge->create_table('smartbe_settings', true);
    $this->db->query('ALTER TABLE `smartbe_settings` ENGINE = InnoDB');

    # seed the settings db
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("1", "application_url_auto", "on", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("2", "application_url", "", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("3", "application_title", "CI App", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("4", "application_sentry_status", "off", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("5", "application_sentry_dsn", "", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("7", "application_sentry_dsn_public", "", null, null, null)');
    $this->db->query('INSERT INTO `smartbe_settings` (`setting_id`, `setting_code`, `setting_value`, `setting_changed_date`, `setting_changed_by`, `setting_changed_audit_id`) VALUES ("8", "application_logo", "", null, null, null)');

    ## Create Table smartbe_users
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
    $this->dbforge->create_table('smartbe_users', true);
    $this->db->query('ALTER TABLE  `smartbe_users` ENGINE = InnoDB');

    $this->db->query('INSERT INTO `smartbe_users` (`user_id`, `user_name`, `user_mail`, `user_full_name`, `user_password`, `user_created_date`, `user_created_by`, `user_last_change_date`, `user_last_change_by`, `user_last_change_audit_id`, `user_role`, `user_status`, `user_removed`, `user_removed_date`)
    VALUES(NULL, "admin", "support@software-dep.net", "Admin", "08c1df5f950179814d83a5ac0a6ceba42a0b9da6", "'.date('Y-m-d H:i:s').'", "0", NULL, NULL, NULL, "admin", "1", "0", NULL)');
  }

  public function down()	{

    ### Drop table smartbe_audit ##
    $this->dbforge->drop_table('smartbe_audit', true);

    ### Drop table smartbe_migrations ##
    $this->dbforge->drop_table('smartbe_migrations', true);

    ### Drop table smartbe_sessions ##
    $this->dbforge->drop_table('smartbe_sessions', true);

    ### Drop table smartbe_settings ##
    $this->dbforge->drop_table('smartbe_settings', true);

    ### Drop table smartbe_users ##
    $this->dbforge->drop_table('smartbe_users', true);

  }
}
