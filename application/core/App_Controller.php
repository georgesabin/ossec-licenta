<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_Controller extends CI_Controller {

  // Python Module Dates
  protected $serverIP;
  protected $username;
  protected $password;

  public $publicRoutes = ['login', 'app/loginAction', 'cli/gitHook'];
  public $errorSentry;
  public $errorSentryHandler;
  public $setings;
  public $baseURL;

  public function __construct() {

    parent::__construct();

    // Python Module Dates
    $this->serverIP = 'http://192.168.114.132:5000/';
    $this->username = 'sabin';
    $this->password = 'python';

    // Check for migrations and run them if there are new versions
    if (isset($this->migration) && $this->migration->latest() === FALSE) {
      show_error($this->migration->error_string());
    }

    $this->settings = $this->settings_model->getSettingsObject();

    // if ($this->settings->application_sentry_status === 'on') {
    //   $this->errorSentry          = new Raven_Client($this->settings->application_sentry_dsn);
    //   $this->errorSentryHandler   = new Raven_ErrorHandler($this->errorSentry);
    //   $this->errorSentryHandler->registerExceptionHandler();
    //   $this->errorSentryHandler->registerErrorHandler();
    //   $this->errorSentryHandler->registerShutdownFunction();
    // }

    $this->baseURL = isset($this->settings->application_url_auto) && isset($this->settings->application_url) && $this->settings->application_url != '' && !$this->settings->application_url_auto ? $this->settings->application_url : $this->config->item('base_url');


    // Detect if the client is logged in
    if($this->session->sessionKey != null) {
      if(!$this->users_model->checkUserSession($this->session->sessionKey)) {
        $this->session->sessionKey = null;
        delete_cookie('userId');
        delete_cookie('userFullName');
        delete_cookie('userName');
        header('Location: '.$this->config->item('base_url').'login');
      }else{
        $this->session->publicKey = null;
        if($this->router->fetch_method() == 'login' || $this->router->fetch_method() == 'loginAction') {
          header('Location: '.$this->config->item('base_url'));
        }
      }
    }

    // Detect public key if it is sent by client and check if it is valid
    if($this->input->get('publicKey') != '' || $this->session->publicKey != null) {

      // Load the keys_model
      $this->load->model('keys_model');

      // Get the public key ( from REQUEST or from SESSION, REQUEST overwrites SESSION if is set )
      $publicKey 	= $this->input->get('publicKey') != '' ? $this->input->get('publicKey') : $this->session->publicKey;
      $getKey 	= $this->keys_model->getKeyByCode($publicKey);

    }

    // If the user is not logged or does not have a public key, redirect him to the login form
    if($this->session->sessionKey == null  && !in_array($this->router->uri->uri_string, $this->publicRoutes)) {
      header('Location: '.$this->config->item('base_url').'login');
    }

  }

  public function uploadFile($uploadData) {
    $config = [];
    $config['upload_path']      = './files/'.$uploadData['upload_path'];
    $config['allowed_types']    = $uploadData['allowed_types'];
    $config['max_size']         = isset($uploadData['max_size']) ? $uploadData['max_size'] : '2048';
    $config['encrypt_name']     = true;

    $this->load->library('upload', $config);

    $this->upload->initialize($config);

    if(!$this->upload->do_upload($uploadData['file_name'])) {
      return (object)['status' => false, 'error' => $this->upload->display_errors('', '')];
    }else{
      return json_decode(json_encode(['status' => true, 'data' => $this->upload->data()]), false);
    }
  }

}
