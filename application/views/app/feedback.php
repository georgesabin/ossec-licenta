<div class="modal-dialog">
  <div class="blockLoader modalLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">×</button>
      <h4 class="modal-title">Send some feedback</h4>
      <small>We would love to get some feedback from you, positive or negative, we will read them both.</small>
    </div>
    <div class="modal-body">
      <div id="feedback"></div>
      <form method="POST" action="<?= $this->config->item('base_url');?>app/feedbackAction" id="feedbackAction">
        <input type="hidden" class="formToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"/>
        <label class="required">Message</label>
        <textarea name="feedback_message" rows="8" cols="80" class="form-control"></textarea>
        <span class="label_error">&nbsp;</span>
      </form>
    </div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-xs-12">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-info pull-right btn-feedback" onclick="submit_form('#feedbackAction', '#feedback', false, formCallback, false);"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> Send feedback</button>
        </div>
      </div>
    </div>
  </div>
</div>
