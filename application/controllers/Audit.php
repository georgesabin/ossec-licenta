<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit extends App_Controller {

  public function __construct() {

    parent::__construct();

  }

  public function index(){

    $usersFullNames = $this->audit_model->getUsersFullNames();

    // Load the view
    $this->load->view(
      'audit/index',
      [
        'usersFullNames' => $usersFullNames,
      ]
    );

  }

  public function getAudit() {

    // Define default return
    $returnData = [];
    $userData   = [];

    // Get the audit data
    $returnData = $this->audit_model->getAudit($this->input->post('date_from_audit'), $this->input->post('date_to_audit'), $this->input->post('user'), $this->input->post('log_action'), $this->input->post('target_type'));
    $returnDataTotal = $this->audit_model->getAuditCount($this->input->post('date_from_audit'), $this->input->post('date_to_audit'), $this->input->post('user'), $this->input->post('log_action'), $this->input->post('target_type'));

    foreach ($returnData as &$row) {

      $row->log_action = $row->log_action.'<br />'.$row->log_date;

      if(strlen($row->log_value) <= 2500) {
        $row->log_value_original = json_decode($row->log_value);
        if (isset($row->log_value_original->before) && isset($row->log_value_original->after)) {

          $row->log_value = '<table class="table  table-bordered"><thead><tr><th style="min-width: 50px;">Key <span class="label label-default label-xs toggleDetails onlyChanges">show everything</span></th><th style="min-width: 50px;">Before</th><th style="min-width: 50px;">After</th></thead>';
          foreach ($row->log_value_original->after as $logKey => $logRow) {
            $row->log_value .= '<tr class="'.(@$row->log_value_original->before->{$logKey} == $logRow ? 'same' : '').'" style="'.(@$row->log_value_original->before->{$logKey} == $logRow ? 'display: none;' : '').'"><td width="200">'.$logKey.'</td><td>'.nl2br(@$row->log_value_original->before->{$logKey}).'</td><td>'.nl2br($logRow).'</td></tr>';
          }
          $row->log_value .= '</table>';

        } elseif (is_object($row->log_value_original)) {

          $row->log_value = '<table class="table  table-bordered"><thead><tr><th>Key</th><th>Value</th></thead>';
          foreach ($row->log_value_original as $logKey => $logRow) {
            $row->log_value .= '<tr><td width="200">'.$logKey.'</td><td>'.($logRow === false ? '0' : $logRow).'</td></tr>';
          }
          $row->log_value .= '</table>';

        }else{
          if ($row->log_value != '') { $row->log_value = '<pre class="audit_value">' . print_r(json_decode($row->log_value), true) . '</pre>'; }
        }
      }else{
        $row->log_value = '<i>This log contains a large text, click <span class="label label-info label-xs label-action" onclick="js_modal(\'modal_container\', \'audit/log/'.$row->log_id.'\');">here</span> to see everything about his log.</i>';
      }
      if($row->log_value == '') {
        $row->log_value = '<i>No data.</i>';
      }

      if($row->log_user_id > 0) {
        $row->log_user_data = $this->users_model->getUserById($row->log_user_id);
        if(isset($row->log_user_data->user_name)) {
          $row->log_user_avatar = (isset($row->log_user_data) && !in_array($row->log_user_data->user_avatar, ['assets/img/faces/face-1.jpg', ''])) ? 'files/app/' . $row->log_user_data->user_avatar : base_url() . 'assets/img/faces/face-1.jpg';
          $row->log_user = '<div class="chip" onclick="js_modal(\'modal_container\', \'app/user/'.$row->log_user_id.'\'); return false;"><img src="'.$row->log_user_avatar.'" alt="" width="48" height="48">'.$row->log_user.'</div>';
        }else{
          $row->log_user = '<div class="chip chip_removed"><img src="assets/img/faces/face-0.jpg" alt="" width="48" height="48">unknown</div>';
        }
      }else{
        $row->log_user = '<div class="chip chip_removed"><img src="assets/img/faces/face-0.jpg" alt="" width="48" height="48">unknown</div>';
      }

      if($row->log_target_entity_type == 'user' && $row->log_target_entity_id > 0) {
        $row->log_target_data = $this->users_model->getUserById($row->log_target_entity_id);
        if(isset($row->log_target_data->user_name)) {
          $row->log_target_avatar = (isset($row->log_target_data) && !in_array($row->log_target_data->user_avatar, ['assets/img/faces/face-1.jpg', ''])) ? 'files/app/' . $row->log_target_data->user_avatar : base_url() . 'assets/img/faces/face-1.jpg';
          $row->log_target_entity_id = '<div class="chip" onclick="js_modal(\'modal_container\', \'app/user/'.$row->log_target_entity_id.'\'); return false;"><img src="'.$row->log_target_avatar.'" alt="" width="48" height="48">'.@$row->log_target_data->user_full_name.'</div>';
        }else{
          $row->log_target_entity_id = '<div class="chip chip_removed"><img src="assets/img/faces/face-0.jpg" alt="" width="48" height="48">unknown</div>';
        }
      }

    }

    // Return json for datatable
    echo json_encode([
      'data'              => $returnData,
      'recordsTotal'      => (int)@$returnDataTotal->total,
      'recordsFiltered'   => (int)@$returnDataTotal->total
    ]);

  }

  public function log(int $logId) {

    // Check if the logId is sent, view the log
    if (isset($logId)) {
      $getLog = $this->audit_model->getLog($logId);

      if (isset($getLog)) {
        $getLog->log_last_change_user = $this->users_model->getUserById((int)$getLog->log_user_id);
        $getLog->log_target_entity_user = $this->users_model->getUserById((int)$getLog->log_target_entity_id);
      }
    }

    // Load the log modal view
    $this->load->view(
      'audit/log',
      [
        'log' => $getLog
      ]
    );

  }

}
