<div class="modal-dialog">
  <div class="blockLoader modalLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">×</button>
      <h4 class="modal-title">Password confirmation</h4>
      <small>This action requires your password as a confirmation.</small>
    </div>
    <div class="modal-body">
      <div id="passwordVerification"></div>
      <form method="POST" action="<?= $this->config->item('base_url');?>app/passwordVerificationAction" id="passwordVerificationAction">
        <input type="hidden" class="formToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"/>
        <label class="required">Password</label>
        <input type="password" class="form-control" name="verification_password" value="" placeholder="*****"/>
        <span class="label_error">&nbsp;</span>
      </form>
    </div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-xs-12">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-danger pull-right btn-passwordVerification" onclick="submit_form('#passwordVerificationAction', '#passwordVerification', false, formCallback, false);"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Confirm</button>
        </div>
      </div>
    </div>
  </div>
</div>
