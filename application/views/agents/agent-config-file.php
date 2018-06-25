
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">#<?php echo $agent_id . ' ' . $agent_name; ?></h4>
      </div>
      <div class="modal-body">
        <div id="saveAgentConfigFileResult"></div>
        <form action="/agent/createAgentConfFile" method="POST" id="saveAgentConfigFile">
          <input type="hidden" class="formToken" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="agent_id" value="<?php echo $agent_id; ?>">
          <input type="hidden" name="agent_name" value="<?php echo $agent_name; ?>">
          <div class="row">
            <div class="col-md-12">
              <label for="basic-url">Config</label>
              <textarea class="form-control" name="agent_conf" rows="15"><?php echo $agent_conf; ?></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
        <button type="button" class="btn btn-primary" onclick="submit_form('#saveAgentConfigFile', '#saveAgentConfigFileResult', false, false, {});">Salveaza</button>
      </div>
    </div>
  </div>