<div class="modal-dialog" <?= isset($log) && $log->log_action == 'setting.update' ? 'style="width: 80%"': ''; ?>>
  <div class="blockLoader modalLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Log<?= isset($log) ? ' ( #'.$log->log_id.' )' : ''; ?></h4>
    </div>
    <div class="modal-body">
      <div class="row">
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">ID</label>
          <span class="label_error">&nbsp;</span>
          <label><?= $log->log_id; ?></label>
        </div>
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">Action</label>
          <span class="label_error">&nbsp;</span>
          <label><?= $log->log_action; ?></label>
        </div>
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">Date</label>
          <span class="label_error">&nbsp;</span>
          <label><?= $log->log_date; ?></label>
        </div>
      </div>
      <span class="label_error">&nbsp;</span>
      <div class="row">
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">User</label>
          <span class="label_error">&nbsp;</span>
          <label>
            <?php if(isset($log->log_last_change_user->user_mail)) { ?>
              <div class="chip" onclick="js_modal('modal_container_3', 'app/user/<?= $log->log_last_change_user->user_id; ?>'); return false;">
                <img src="<?= (isset($log->log_last_change_user) && !in_array($log->log_last_change_user->user_avatar, ['assets/img/faces/face-1.jpg', ''])) ? 'files/app/' . $log->log_last_change_user->user_avatar : base_url() . 'assets/img/faces/face-1.jpg'; ?>" alt="" width="48" height="48">
                <?= $log->log_last_change_user->user_full_name; ?>
              </div>
            <?php }else{ ?>
              Guest
            <?php } ?>
          </label>
        </div>
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">Target Entity Type</label>
          <span class="label_error">&nbsp;</span>
          <label><?= $log->log_target_entity_type; ?></label>
        </div>
        <div class="col-lg-4">
          <label style="font-weight: bold;" class="not_required">Target Entity</label>
          <span class="label_error">&nbsp;</span>
          <label>
            <?php if(isset($log->log_target_entity_user)) { ?>
              <div class="chip" onclick="js_modal('modal_container_3', 'app/user/<?= $log->log_target_entity_user->user_id; ?>'); return false;">
                <img src="<?= (isset($log->log_target_entity_user) && !in_array($log->log_target_entity_user->user_avatar, ['assets/img/faces/face-1.jpg', ''])) ? 'files/app/' . $log->log_target_entity_user->user_avatar : base_url() . 'assets/img/faces/face-1.jpg'; ?>" alt="" width="48" height="48">
                <?= $log->log_target_entity_user->user_full_name; ?>
              </div>
            <?php }else{ ?>
              <?= $log->log_target_entity_id; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      <span class="label_error">&nbsp;</span>
      <div class="row">
        <div class="col-lg-12">
          <label style="font-weight: bold;" class="not_required">Changes</label>
          <span class="label_error">&nbsp;</span>
          <?php
          if ($log->log_value != '') {

            $log->log_value_object = json_decode(@$log->log_value);

            if(isset($log->log_value_object->before) && isset($log->log_value_object->after)) {
              echo '<table class="table table-bordered"><thead><tr><th style="min-width: 50px;">Key <span class="label label-default label-xs toggleDetails onlyChanges">show everything</span></th><th style="min-width: 50px;">Before</th><th style="min-width: 50px;">After</th></thead>';
              foreach ($log->log_value_object->after as $logKey => $logRow) {
                echo '<tr class="'.(@$log->log_value_object->before->{$logKey} == $logRow ? 'same' : '').'" style="'.(@$log->log_value_object->before->{$logKey} == $logRow ? 'display: none;' : '').'"><td width="200">'.$logKey.'</td><td>'.nl2br(@$log->log_value_object->before->{$logKey}).'</td><td>'.nl2br($logRow).'</td></tr>';
              }
              echo '</table>';
            } elseif (is_object($log->log_value_object)) {

              echo '<table class="table table-bordered"><thead><tr><th>Key</th><th>Value</th></thead>';
              foreach ($log->log_value_object as $logKey => $logRow) {
                echo '<tr><td width="200">'.$logKey.'</td><td>'.($logRow === false ? '0' : $logRow).'</td></tr>';
              }
              echo '</table>';

            }else{
              echo '<pre class="audit_value">'.print_r((object)json_decode($log->log_value)).'</pre>';
            }
          }
          ?>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
  </div>
</div>
