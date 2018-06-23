<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends App_Controller {

  public function __construct() {

    parent::__construct();

  }

  public function serverAction($cmd_type) {

    $url = $this->serverIP . 'server/' . $cmd_type;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($ch);
    curl_close($ch);

  }

  public function my_profile() {

    $allActions = $this->users_model->getLoginRulesUsers($this->session->userId);
    $userData = [];
    foreach ($allActions as $key => $value) {
      $userData[$value->log_action] = $value->total;
    }

    $this->load->view(
      'app/my_profile',
      [
        'dates' => $this->users_model->getUserById($this->session->userId),
        'actionsUser' => (object)$userData
      ]
    );

  }

  public function user($userId = 0) {
    $this->load->view(
        'app/user',
        [
          'user' => $this->users_model->getUserById($userId)
        ]
    );
  }

  public function updateAction() {

    $userId = $this->session->userId;

    // Load the form validation
    $this->load->library('form_validation');

    // Data post
    $data = [
      'email'     => $this->input->post('user_mail'),
      'full_name' => $this->input->post('user_full_name')
    ];

    if($this->input->post('new_password') != '') { $data['password'] = sha1($this->config->item('encryption_key') . $this->input->post('new_password')); }

    // Get the current user's data ( before update )
    $getUser = $userId > 0 ? $this->users_model->getUserById($userId) : null;

    $beforeData = [
      'email'     => $getUser->user_mail,
      'full_name' => $getUser->user_full_name,
      'password'  => $getUser->user_password,
      'avatar'    => $getUser->user_avatar
    ];

    // Add unique mail to form validation if the user tries to change the mail
    $userMailUnique = isset($getUser) && $getUser->user_mail !== $data['email'] ? '|is_unique[smartbe_users.user_mail]' : '';

    // Define rules
    $formValidationRules = [
      ['field' => 'user_mail', 'rules' => 'trim|required|valid_email' . $userMailUnique, 'label' => 'Email address'],
      ['field' => 'user_full_name', 'rules' => 'trim|required', 'label' => 'Full Name']
    ];

    // Add the password to form validation if the user tries to change the password
    if($this->input->post('new_password') != '') {
      $formValidationRules[] = ['field' => 'new_password', 'rules' => 'trim|required|min_length[6]' . $userMailUnique, 'label' => 'New password'];
      $formValidationRules[] = ['field' => 'repeat_password', 'rules' => 'trim|required' . $userMailUnique, 'label' => 'Repeat new password'];
    }

    // Set the rules
    $this->form_validation->set_rules($formValidationRules);

    // Validate the form
    if ($this->form_validation->run() === false) {
      echo json_encode(['has_errors' => true, 'errors' => $this->form_validation->error_array()]);
      exit;
    }

    // Check if the password are the same if the user tries to change the password
    if($this->input->post('new_password') != '' && $this->input->post('new_password') != $this->input->post('repeat_password')) { echo json_encode(['has_errors' => true, 'errors' => ['repeat_password' => 'The Repeat new password should be the same as New password']]); exit; }

    // Check and upload logo image
    if (isset($_FILES['user_avatar']['name'])) {
      $uploadAvatar = $this->uploadFile(
        [
          'file_name'     => 'user_avatar',
          'upload_path'   => 'app/',
          'allowed_types' => ['png', 'jpg', 'jpeg']
        ]
      );
      if($uploadAvatar === false) { echo json_encode(['has_errors' => true, 'errors' => ['user_avatar' => $uploadLogo->error]]); exit; }
    }

    if (isset($uploadAvatar->data->file_name)) { $data['avatar'] = $uploadAvatar->data->file_name; }
    if (!isset($uploadAvatar->data->file_name) && $this->input->post('user_avatar_default') == '1') { $data['avatar'] = 'assets/img/faces/face-1.jpg'; }

    // Prepare the updateData
    $updateData = [];
    if(isset($data) && $getUser->user_mail !== $data['email']) {
      $updateData['user_mail'] = $data['email'];
    }
    if(isset($data) && $getUser->user_full_name !== $data['full_name']) {
      $updateData['user_full_name'] = $data['full_name'];
    }
    if(isset($data) && isset($data['avatar']) && $getUser->user_avatar !== $data['avatar']) {
      $updateData['user_avatar'] = $data['avatar'];
    }
    if(isset($data) && isset($data['password']) && $getUser->user_password !== $data['password']) {
      $updateData['user_password'] = $data['password'];
    }

    // If there is anything to update
    if(count($updateData) > 0) {

      $updateData['user_last_change_date']      = date('Y-m-d H:i:s');
      $this->users_model->updateUser($userId, $updateData);

    }

  }

  public function login() {

    // Check if the user wants to login with a new account ( not one saved in session )
    if(isset($_GET['force'])) {
      delete_cookie('userId');
      delete_cookie('userFullName');
      delete_cookie('userName');
      header('Location: '.$this->config->item('base_url').'login');
    }

    // Load the login view
    $this->load->view('app/login');

  }

  public function loginAction() {

    // Load the form validation
    $this->load->library('form_validation');

    // Set the form validation rules
    $this->form_validation->set_rules('user_name', 'Username', 'trim|required');
    $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

    // If the form validation fails, show the errors else check the password and try to create a new session
    if ($this->form_validation->run() == FALSE) {

      echo validation_errors();

    }else{

      // Check the password and if it is valid, create a new session and login, if the password is invalid show the errors
      $checkUserPassword = $this->users_model->checkPassword($this->input->post('user_name'), sha1($this->config->item('encryption_key').$this->input->post('user_password')));
      $getUser = $this->users_model->getUserById($checkUserPassword);

      if($checkUserPassword === false) {
        echo '{"has_errors":true,"errors":{"user_name":"User might be incorrect or the account is disabled.", "user_password":"Password might be incorrect"}}';
        return;
      }else{

        // Create the session
        $this->session->sessionKey  = $this->users_model->createSession($checkUserPassword);
        $this->session->userId      = $checkUserPassword;

        set_cookie('userId', $checkUserPassword, 604800);
        set_cookie('userFullName', $getUser->user_full_name, 604800);
        set_cookie('userName', $getUser->user_name, 604800);
        set_cookie('userAvatar', $getUser->user_avatar, 604800);

        echo '<script type="text/javascript">location.href = "'.$this->config->item('base_url').'";</script>';
      }

    }
  }

  public function logout() {

    // Check if the user is logged in with an account and remove the data from the session
    if($this->session->sessionKey != null) {

      $this->users_model->destroySession($this->session->sessionKey);
      $this->session->sessionKey  = null;
      $this->session->userId      = null;
      delete_cookie('userId');
      delete_cookie('userFullName');
      delete_cookie('userName');
      delete_cookie('userAvatar');
    }

    // Check if the user is logged in with a public key and remove the data from the session
    if($this->session->publicKey != null) {
      $this->session->publicKey = null;
    }

    // Redirect the client to the base url ( should be redirected to login from there if everything was ok )
    header('Location: '.$this->config->item('base_url'));

  }

  public function passwordVerification() {

    // Load view for verification with password
    $this->load->view(
      'app/verification'
    );

  }

  public function passwordVerificationAction() {

    // Get the user dates by id
    $user = $this->users_model->getUserById($this->session->userId);

    // Form validation
    $this->load->library('form_validation');

    // Define rule
    $formValidationRules = ['field' => 'verification_password', 'rules' => 'trim|required', 'label' => 'Password'];

    $checkUserPassword = $this->users_model->checkPassword($user->user_name, sha1($this->config->item('encryption_key') . $this->input->post('verification_password')));

    if ($checkUserPassword === false) {
      echo json_encode(['has_errors' => true, 'errors' => ['verification_password' => 'The password is incorrect. Try again!']]); exit;
    } else {
      echo json_encode(['true']);
    }
  }

}
