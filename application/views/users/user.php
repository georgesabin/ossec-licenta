<div class="modal-dialog">
  <div class="blockLoader modalLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><?= !isset($user) ? 'New ' : ''; ?>User<?= isset($user) ? ' ( #'.$user->user_id.' )' : ''; ?></h4>
      <?php if(isset($user) && isset($user->user_last_change_user)) { ?>
        <small>Last change was by <a href="#" onclick="js_modal('modal_container_3', 'app/user/<?= $user->user_last_change_user->user_id; ?>'); return false;"><span class="label label-default label-xs"><?= $user->user_last_change_user->user_full_name; ?></span></a> on <a href="#" onclick="js_modal('modal_container_2', 'audit/log/<?= $user->user_last_change_audit_id; ?>'); return false;"><span class="label label-default label-xs"><?= $user->user_last_change_date; ?></span></a></small>
      <?php } ?>
    </div>
    <div class="modal-body">
      <div id="saveUserResult"></div>
      <form method="POST" action="<?= $this->config->item('base_url');?>users/saveUser" id="saveUser">
        <input type="hidden" name="user_id" value="<?= isset($user) ? $user->user_id : '0'; ?>" />
        <input type="hidden" class="formToken" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
        <div class="row">
          <div class="col-lg-6">
            <label class="required">User name</label>
            <input type="text" class="form-control required" name="user_name" value="<?= isset($user) ? $user->user_name : '';?>"/>
            <span class="label_error">&nbsp;</span>
          </div>
          <div class="col-lg-3">
            <label class="required">User role</label>
            <select class="form-control" name="user_role">
              <option value="">-- Select role</option>
              <option value="admin" <?= isset($user) && $user->user_role == 'admin' ? 'selected' : '';?>>Admin</option>
              <option value="manager" <?= isset($user) && $user->user_role == 'manager' ? 'selected' : '';?>>Manager</option>
            </select>
            <span class="label_error">&nbsp;</span>
          </div>
          <div class="col-lg-3">
            <label class="required">Status</label>
            <input type="checkbox" class="form-control required bootstrap-switch" name="user_status" <?= isset($user) && !$user->user_status ? '' : 'checked'; ?> data-size="sm" data-off-color="danger" data-on-color="success" data-on-text="ON" data-off-text="OFF"/>
            <span class="label_error">&nbsp;</span>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-lg-6">
            <label class="required">User mail</label>
            <input type="mail" class="form-control required" name="user_mail" value="<?= isset($user) ? $user->user_mail : '';?>"/>
            <span class="label_error">&nbsp;</span>
          </div>
          <div class="col-lg-6">
            <label class="required">User full name</label>
            <input type="text" class="form-control required" name="user_full_name" value="<?= isset($user) ? $user->user_full_name : '';?>"/>
            <span class="label_error">&nbsp;</span>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-lg-6">
            <label <?= !isset($user) ? ' class="required"' : ''; ?>>Password <?= isset($user) ? '<small><i>( type to change )</i></small>' : ''; ?></label>
            <input type="password" class="form-control <?= !isset($user) ? 'required' : ''; ?>" name="user_password" value="" placeholder="*****"/>
            <span class="label_error">&nbsp;</span>
          </div>
          <div class="col-lg-6">
            <label <?= !isset($user) ? ' class="required"' : ''; ?>>Repeat password <?= isset($user) ? '<small><i>( type to change )</i></small>' : ''; ?></label>
            <input type="password" class="form-control <?= !isset($user) ? 'required' : ''; ?>" name="user_password_confirmation" value="" placeholder="*****"/>
            <span class="label_error">&nbsp;</span>
          </div>
        </div>
        <br />
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
      <button type="button" class="btn btn-success" onclick="submit_form('#saveUser', '#saveUserResult', false, false, {})"><i class="fa fa-save"></i> Save</button>
    </div>
  </div>
  <script type="text/javascript">
  $(".bootstrap-switch").bootstrapSwitch({size: 'large', handleWidth: 30, labelWidth: 5});
  </script>
</div>
