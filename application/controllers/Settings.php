<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends App_Controller {

  public function __construct() {

    parent::__construct();
    $this->load->model('settings_model');
    $this->load->model('users_model');
    $this->load->model('audit_model');

  }

  public function index() {

    // Get all settings
    $currentSettings = $this->settings_model->getSettings();

    // Array with codes
    $currentSettingCode = [];

    foreach ($currentSettings as $key => $value) {
      $currentSettingCode[$value->setting_code] = (array)$currentSettings[$key];
    }

    // Get the content from ossec.conf file via python-module
    $url = $this->serverIP . 'server/confFile';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $confFile = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    curl_close($ch);
    $confFileObj = json_decode($confFile);

    // Load the settings view
    $this->load->view(
      'settings/index',
      [
        'settings' => $currentSettingCode,
        'confFile' => $confFileObj->response
      ]
    );

  }

  /**
  *	Save General Settings
  **/

  public function saveGeneralSettings() {

    $settings = ['application_url_auto','application_url','application_title','application_logo'];

    // Form validation
    $this->load->library('form_validation');

    // Define rules
    $formValidationRules = [
      [
        'field' => 'application_url',
        'rules' => 'trim',
        'label' => 'URL'
      ],
      [
        'filed' => 'application_title',
        'rules' => 'trim',
        'label' => 'Title'
      ],
    ];

    // Set the rules
    $this->form_validation->set_rules($formValidationRules);

    // Validate the form
    if($this->form_validation->run() === false) {
      echo json_encode(['has_errors' => true, 'errors' => $this->form_validation->error_array()]);
      exit;
    }

    // Check and upload logo image
    if (isset($_FILES['application_logo']['name'])) {
      $uploadLogo = $this->uploadFile(
        [
          'file_name'     => 'application_logo',
          'upload_path'   => 'app/',
          'allowed_types' => ['png', 'jpg', 'jpeg']
        ]
      );
      if($uploadLogo === false) { echo json_encode(['has_errors' => true, 'errors' => ['rule_intro_path' => $uploadLogo->error]]); exit; }
    }

    // Get all settings
    $currentSettings = $this->settings_model->getSettings();

    // Array with codes
    $currentSettingCode = [];

    foreach ($currentSettings as $key => $value) {
      $currentSettingCode[$value->setting_code] = (array)$currentSettings[$key];
    }

    $currentSetting = [];

    foreach ($settings as $key => $value) {
      $currentSetting[$value] = $currentSettingCode[$value]['setting_value'];
    }

    // Create array with the dates
    $settingsData = [
      'application_url_auto'      => $this->input->post('application_url_auto') == null ? 'off' : $this->input->post('application_url_auto'),
      'application_url'           => $this->input->post('application_url'),
      'application_title'         => $this->input->post('application_title'),
      'logo_removed'              => $this->input->post('logo_removed')
    ];

    // If the user uploaded the logo, insert file name into table else check if the logo is BSS ONE remove file name from table
    if (isset($settingsData['logo_removed']) && $settingsData['logo_removed'] === 'Removed Logo') {
      $settingsData['application_logo'] = '';
    } else if(isset($uploadLogo->data->file_name)) {
      $settingsData['application_logo'] = $uploadLogo->data->file_name;
    } else {
      $settingsData['application_logo'] = '';
    }

    foreach ($settings as $key => $value) {
     if (isset($settingsData[$value]) && $currentSettingCode[$value]['setting_value'] != $settingsData[$value]) {
       // Get audit id
       $auditId = $this->audit_model->log([
         'log_action'             => 'setting.update',
         'log_user_id'            => $this->session->userId,
         'log_target_entity_type' => 'setting',
         'log_target_entity_id'   => $currentSettingCode[$value]['setting_id'],
         'log_value'              => json_encode((object)['before' => [$value => $currentSetting[$value]], 'after' => [$value => $settingsData[$value]]])
       ]);
       $this->settings_model->updateSettings($value, ['setting_value' => $settingsData[$value], 'setting_changed_date' => date('Y-m-d h:m:s'), 'setting_changed_by' => $this->session->userId, 'setting_changed_audit_id' => $auditId]);
     }
   }

  }

  public function saveOssecConf() {

    // echo str_replace('"', '', json_encode($this->input->post('ossec_conf_file')));
    // exit;
    $url = $this->serverIP . 'server/replaceConfFile';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        // 'server_conf'    => str_replace('"', '', json_encode($this->input->post('ossec_conf_file'))),
        'server_conf'    => $this->input->post('ossec_conf_file'),
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $result = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    curl_close($ch);
    echo $result;

  }

  /**
  *	Save Developer Settings
  **/
  public function saveDeveloperSettings() {

    $settings = ['application_sentry_status','application_sentry_dsn','application_sentry_dsn_public'];

    // Form validation
    $this->load->library('form_validation');

    // Define rules
    $formValidationRules = [
      [
        'field' => 'application_sentry_dsn',
        'rules' => 'trim',
        'label' => 'DSN'
      ],
      [
        'field' => 'application_sentry_dsn_public',
        'rules' => 'trim',
        'label' => 'DSN (Public)'
      ]
    ];

    // Set the rules
    $this->form_validation->set_rules($formValidationRules);

    // Validate the form
    if($this->form_validation->run() === false) {
      echo json_encode(['has_errors' => true, 'errors' => $this->form_validation->error_array()]);
      exit;
    }

    // Get all settings
    $currentSettings = $this->settings_model->getSettings();

    // Array with codes
    $currentSettingCode = [];

    foreach ($currentSettings as $key => $value) {
      $currentSettingCode[$value->setting_code] = (array)$currentSettings[$key];
    }

    $currentSetting = [];

    foreach ($settings as $key => $value) {
      $currentSetting[$value] = $currentSettingCode[$value]['setting_value'];
    }

    // Create array with the dates
    $settingsData = [
      'application_sentry_status'     => $this->input->post('application_sentry_status') == null ? 'off' : $this->input->post('application_sentry_status'),
      'application_sentry_dsn'        => $this->input->post('application_sentry_dsn'),
      'application_sentry_dsn_public' => $this->input->post('application_sentry_dsn_public')
    ];

    foreach ($settings as $key => $value) {
      if (isset($settingsData[$value]) && $currentSettingCode[$value]['setting_value'] != $settingsData[$value]) {
        // Get audit id
        $auditId = $this->audit_model->log([
          'log_action'             => 'setting.update',
          'log_user_id'            => $this->session->userId,
          'log_target_entity_type' => 'setting',
          'log_target_entity_id'   => $currentSettingCode[$value]['setting_id'],
          'log_value'              => json_encode((object)['before' => [$value => $currentSetting[$value]], 'after' => [$value => $settingsData[$value]]])
        ]);
        $this->settings_model->updateSettings($value, ['setting_value' => $settingsData[$value], 'setting_changed_date' => date('Y-m-d h:m:s'), 'setting_changed_by' => $this->session->userId, 'setting_changed_audit_id' => $auditId]);
      }
    }

  }

}
