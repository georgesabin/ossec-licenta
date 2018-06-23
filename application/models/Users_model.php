<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends App_Model {

  public function __construct() {

    parent::__construct();

  }

  /**
  * Gets a user by ID
  *
  * @param int userId The id of the user
  *
  * @return object
  **/
  public function getUserById(int $userId) {
    return $this->db->select('*')->from('users')->where('user_id', $userId)->where('user_removed', 0)->get()->row();
  }

  /**
  * Gets a user by userName
  *
  * @param string userName The username of the user
  *
  * @return object
  **/
  public function getUserByUserName(string $userName) {
    return $this->db->select('*')->from('users')->where('user_name', $userName)->where('user_removed', 0)->get()->row();
  }

  /**
  * Check if the password inserted is ok
  *
  * @param string userName The username of the user
  * @param string userPassword The password of the user
  *
  * @return bool
  **/
  public function checkPassword(string $userName, string $userPassword) {

    $checkUser = $this->db->select('*')->from('users')->where('user_name', $userName)->where('user_password', $userPassword)->where('user_removed', 0)->get()->row();

    return isset($checkUser->user_id) ? $checkUser->user_id : false;

  }

  /**
  * Check if the session is valid
  *
  * @param string sessionKey The session of key
  *
  * @return bool
  **/
  public function checkUserSession(string $sessionKey) {

    $getSession = $this->db->select('*')->from('sessions')->where('session_key', $sessionKey)->get()->row();

    if(isset($getSession->session_id)) {
      $this->db->where('session_key', $sessionKey)->update(
        'sessions',
        ['session_last_used' => date('Y-m-d H:i:s')]
      );
      return true;
    }

    return false;

  }

  /**
  * Create a new session for a user
  *
  * @param int userId The id of the user
  *
  * @return int
  **/
  public function createSession(int $userId) {

    // Generate the session key
    $sessionKey = rand(1000, 9999).time().rand(1000, 9999);

    $this->db->insert(
      'sessions',
      [
        'session_user_id'       => $userId,
        'session_key'           => $sessionKey,
        'session_date_created'  => date('Y-m-d H:i:s'),
        'session_last_used'     => date('Y-m-d H:i:s')
      ]
    );

    return $sessionKey;

  }

  /**
  * Destroy a session by session key
  *
  * @param string sessionKey The key for the session that's need to be destroyed
  *
  * @return bool
  **/
  public function destroySession(string $sessionKey) {

    $this->db->where('session_key', $sessionKey)->delete('sessions');

    return true;

  }

  /**
  * Destroy user's sessions
  *
  * @param int userId The id of the user
  *
  * @return bool
  **/
  public function destroyUserSessions(int $userId) {

    $this->db->where('session_user_id', $userId)->delete('sessions');
    return true;

  }

  /**
  * Update a user ( based on userId ) with the new data
  *
  * @param int userId The id of the user
  * @param array data Array of the data ( column => value )
  *
  * @return int
  **/
  public function updateUser(int $userId, array $data) {
    return $this->db->where('user_id', $userId)->where('user_removed', 0)->update('users', $data);
  }

  /**
  * Insert a new user with the data
  *
  * @param array data Array of the data ( column => value )
  *
  * @return int/false
  **/
  public function insertUser(array $data) {

    // Insert and return the new user`s id
    return $this->db->insert('users', $data) ? $this->db->insert_id() : false;

  }

  /**
  * Delete a user
  *
  * @param int userId The id of the user
  *
  * @return bool
  **/
  public function deleteUser(int $userId) {
    return $this->db->where('user_id', $userId)->delete('users');
  }

}
