<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends App_Model {

  public function __construct() {

    parent::__construct();

  }

  /**
  * Insert a new log to the audit table
  *
  * @param array data The array of the data
  *
  * @return int
  **/
  public function log(array $data) {

    // Set the date
    $data['log_date'] = date('Y-m-d H:i:s');

    // Insert the log
    $this->db->insert('audit', $data);

    // Return the inserted id
    return $this->db->insert_id();

  }

  /**
  * Gets one log based on the log id from the database
  *
  * @param int logId The id of the log
  *
  * @return object
  **/

  public function getLog(int $logId) {

    return $this->db
      ->select('*')
      ->from('audit')
      ->where('log_id', $logId)->get()->row();

  }

  /**
  * Get the audit
  *
  *
  * @return array
  **/
  public function getAudit(string $date_from_audit = null, string $date_to_audit = null, string $user = null, string $log_action = null, string $target_type = null) {

    // Default query
    $this->db->select('log_id, log_action, log_date, user_full_name as log_user, user_id as log_user_id, log_target_entity_type, log_target_entity_id, log_value')
      ->from('audit')
      ->join('users', 'users.user_id=audit.log_user_id', 'left');

    // Add the conditions: The searching is AND type
    if ($date_from_audit != null) {
      $this->db->where('log_date >=', $date_from_audit . ' 00:00:00');
    } else {
      $this->db->where('log_date >=', date('Y-m-d') . ' 00:00:00');
    }
    if ($date_from_audit != null) {
      $this->db->where('log_date <=', $date_to_audit . ' 23:59:59');
    } else {
      $this->db->where('log_date <=', date('Y-m-d') . ' 23:59:59');
    }
    if ($user != null) { $this->db->where('user_full_name', $user); }
    if ($log_action != null) { $this->db->where('log_action', $log_action); }
    if ($target_type != null) { $this->db->where('log_target_entity_type', $target_type); }

    // Return the data
    return $this->db->order_by('log_id', 'desc')->limit($this->input->post('length'), $this->input->post('start'))->get()->result();

  }

  /**
  * Get the audit totals
  *
  * @return object
  **/
  public function getAuditCount(string $date_from_audit = null, string $date_to_audit = null, string $user = null, string $log_action = null, string $target_type = null) {

    $this->db->select('count(*) as total')->from('audit')->join('users', 'users.user_id=audit.log_user_id', 'left');

    // Add the conditions: The searching is AND type
    if ($date_from_audit != null) {
      $this->db->where('log_date >=', $date_from_audit . ' 00:00:00');
    } else {
      $this->db->where('log_date >=', date('Y-m-d') . ' 00:00:00');
    }
    if ($date_from_audit != null) {
      $this->db->where('log_date <=', $date_to_audit . ' 23:59:59');
    } else {
      $this->db->where('log_date <=', date('Y-m-d') . ' 23:59:59');
    }
    if ($user != null) { $this->db->where('user_full_name', $user); }
    if ($log_action != null) { $this->db->where('log_action', $log_action); }
    if ($target_type != null) { $this->db->where('log_target_entity_type', $target_type); }

    return $this->db->get()->row();

  }

  /**
  * Get the users full name
  *
  * @return object
  **/
  public function getUsersFullNames() {

    return $this->db
      ->select('DISTINCT user_full_name', FALSE)
      ->from('users')
      ->where('user_full_name <>', '')->get()->result();

  }

}
