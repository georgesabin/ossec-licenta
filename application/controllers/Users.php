<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends App_Controller {

  public function __construct() {

    parent::__construct();

  }

  public function index() {

    // Load the users view
    $this->load->view(
      'users/index'
    );

  }

  public function user(int $userId = 0) {

    // Check if the userId is sent ( this means user edit )
    $getUser = $this->users_model->getUserById((int)$userId);
    if(isset($getUser)) { $getUser->user_last_change_user = $this->users_model->getUserById((int)$getUser->user_last_change_by); }

    // Load the user modal view
    $this->load->view(
      'users/user',
      ['user' => $getUser]
    );

  }

  public function getDataTableRows() {

    // Get data from POST
    $data = [
      'user_full_name' => $this->input->post('user_full_name'),
      'user_name'      => $this->input->post('user_name'),
      'user_role'      => $this->input->post('user_role'),
      'user_mail'      => $this->input->post('user_mail'),
      'length'         => $this->input->post('length'),
      'start'          => $this->input->post('start'),
      'order_type'     => $this->input->post('order[0][dir]'),
      'column'         => ''
    ];

    // Switch for ordering
    foreach ($this->input->post('columns') as $key => $value) {
      switch ($this->input->post('order[0][column]')) {
        case $key:
          $data['column'] = $this->input->post('columns['.$key.'][data]');
          break;
        default:
          break;
      }
    }

    // Get the users
    $getUsers = $this->users_model->getUsers($data);

    // Get the total
    $getTotal = $this->users_model->getTotalUsers($data);

    // Add the actions
    foreach($getUsers as &$user) {
      $user->actions = '<button class="btn btn-info btn-fill btn-xs" onclick="js_modal(\'modal_container\', \'users/user/'.$user->user_id.'\')" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></button> ';
      $user->actions .= '<button class="btn btn-danger btn-fill btn-xs" onclick="deleteUser('.$user->user_id.', {});" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>';
      }

      // Return json for datatable
      echo json_encode([
        'data'            => $getUsers,
        'recordsTotal'    => (int)@$getTotal->total,
        'recordsFiltered' => (int)@$getTotal->total
      ]);

    }

    public function saveUser() {

      // Get the user id
      $userId = (int)$this->input->post('user_id');

      // Get the current user's data ( before update )
      $getUser = $userId > 0 ? $this->users_model->getUserById($userId) : null;

      // Form validation
      $this->load->library('form_validation');

      // Define rules and variables to check unique data
      $formValidationRules = [
        ['field' => 'user_full_name', 'rules' => 'trim|required', 'label' => 'Full name'],
        ['field' => 'user_role', 'rules' => 'trim|required', 'label' => 'User role']
      ];
      $userNameUnique = ''; $userMailUnique = '';

      // If this is a new account, add the password to the form validation rules and the user name unique
      if($userId === 0) {
        $formValidationRules[] = ['field' => 'user_password', 'rules' => 'trim|required', 'label' => 'Password'];
        $formValidationRules[] = ['field' => 'user_password_confirmation', 'rules' => 'trim|required', 'label' => 'Repeat password'];
      }

      // Check if the user_name or the user_mail needs to be unique ( if it is new or changed )
      if($userId === 0 || (isset($getUser) && $getUser->user_name != $this->input->post('user_name')) || !isset($getUser)) { $userNameUnique = '|is_unique[smartbe_users.user_name]'; }
      if($userId === 0 || (isset($getUser) && $getUser->user_mail != $this->input->post('user_mail')) || !isset($getUser)) { $userMailUnique = '|is_unique[smartbe_users.user_mail]'; }

      // Add the user_name and user_mail validators
      $formValidationRules[] = ['field' => 'user_mail', 'rules' => 'trim|required|valid_email'.$userMailUnique, 'label' => 'User mail'];
      $formValidationRules[] = ['field' => 'user_name', 'rules' => 'trim|required|min_length[5]|max_length[12]'.$userNameUnique, 'label' => 'User name'];

      // Set the rules
      $this->form_validation->set_rules($formValidationRules);

      // Validate the form
      if($this->form_validation->run() === false) {
        echo json_encode(['has_errors' => true, 'errors' => $this->form_validation->error_array()]);
        exit;
      }
      if($userId === 0) { if($this->input->post('user_password') != $this->input->post('user_password_confirmation')) { echo json_encode(['has_errors' => true, 'errors' => ['user_password_confirmation' => 'The <strong>Repeat password</strong> should be the same as <strong>Password</strong>']]); exit; } }

      $user = $this->users_model->getUserById($userId);
      unset($user->user_id);

      // Build the user data
      $userData = [
        'user_name'             => $this->input->post('user_name'),
        'user_full_name'        => $this->input->post('user_full_name'),
        'user_mail'             => $this->input->post('user_mail'),
        'user_status'           => $this->input->post('user_status') == null ? 0 : 1,
        'user_role'             => $this->input->post('user_role'),
        'user_removed'          => 0,
        'user_last_change_date' => date('Y-m-d H:i:s'),
        'user_last_change_by'   => $this->session->userId
      ];

      if($this->input->post('user_password') != '' && $this->input->post('user_password') != $this->input->post('user_password_confirmation')) {
        echo 'Passwords do not match';
        exit;
      }elseif($this->input->post('user_password') != '') {
        $userData['user_password'] = sha1($this->config->item('encryption_key').$this->input->post('user_password'));
      }

      // If the user id > 0 update the existing user else insert a new one
      if($userId > 0) {

        // Log the action
        $auditId = $this->audit_model->log([
          'log_action'             => 'user.update',
          'log_user_id'            => $this->session->userId,
          'log_target_entity_type' => 'user',
          'log_target_entity_id'   => $userId,
          'log_value'              => json_encode((object)['before' => $user, 'after' => $userData])
        ]);

        // Update the user
        $userData['user_last_change_audit_id'] = $auditId;
        $this->users_model->updateUser($userId, $userData);

      }else{

        // Insert the user
        $userData['user_created_date'] 	= date('Y-m-d H:i:s');
        $userData['user_created_by'] 	= $this->session->userId;
        $newUserId = $this->users_model->insertUser($userData);

        // Log the action
        $this->audit_model->log([
          'log_action'              => 'user.insert',
          'log_user_id'             => $this->session->userId,
          'log_target_entity_type'  => 'user',
          'log_target_entity_id'    => $newUserId,
          'log_value'               => json_encode((object)$userData)
        ]);

      }

      echo '<script type="text/javascript">showLoader($(\'.usersTableLoader\')); usersTable.api().ajax.reload(); $(\'*[data-dismiss="modal"]\').trigger(\'click\');</script>';

    }

    public function deleteUser(int $userId = 0) {

      // Log the action
      $this->audit_model->log([
        'log_action'              => 'user.delete',
        'log_user_id'             => $this->session->userId,
        'log_target_entity_type'  => 'user',
        'log_target_entity_id'    => $userId
      ]);

      $this->users_model->destroyUserSessions($userId);
      echo $this->users_model->updateUser($userId, ['user_removed' => 1, 'user_removed_date' => date('Y-m-d H:i:s')]) ? 'true' : 'false';
    }

  }
