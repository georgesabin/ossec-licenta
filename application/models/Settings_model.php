<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends App_Model {

  public function __construct() {

    parent::__construct();

  }

  /**
  * Update settings
  **/
  public function updateSettings(string $settingCode, array $data) {

    return $this->db
      ->where('setting_code', $settingCode)
      ->update($this->db->dbprefix . 'settings', $data);

  }

  /**
  * Get all settings
  *
  * @return object
  **/
  public function getSettings() {

    return $this->db
      ->select('*')
      ->from($this->db->dbprefix . 'settings')
      ->get()->result();

  }

  /**
  * Get all settings as a object ( with keys )
  *
  * @return object
  **/
  public function getSettingsObject() {

    // Define the vars
    $getSettings = $this->getSettings();
    $output = (object)[];

    // Build the output
    foreach($getSettings as $row) { $output->{$row->setting_code} = $row->setting_value; }

    // Return the output
    return $output;

  }

}
