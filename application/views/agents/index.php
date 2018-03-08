<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'agents',
    'menuActive'        => 'agents/index',
    'pageTitle'         => 'Agents',
    'requireJs'         => ['libs/bootstrap-switch.min.js', 'libs/select2.full.min.js', 'libs/datedropper.min.js', 'pages/agents.js'],
    'requireCSS'        => ['libs/bootstrap-switch.min.css', 'libs/select2.min.css', 'libs/date_filter.css', 'libs/datedropper.min.css']
  ]
); ?>

<div class="row">
  <div class="col-md-12">
    <button type="button" id="add-agent" class="btn btn-primary btn-lg">Adauga Agent</button>
  </div>
</div>

<!-- Add agent modal -->
<div class="modal fade" id="addAgent" tabindex="-1" role="dialog" aria-labelledby="addAgent">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Creeaza agent nou</h4>
      </div>
      <div class="modal-body">
        <div id="saveAgentResult"></div>
        <form action="/agent/addAgent" method="POST" id="saveAgent">
          <input type="hidden" class="formToken" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="alert alert-info">
                <strong id="requestAlert"></strong>
              </div>
            </div>
            <div class="col-md-6">
              <label for="basic-url">Nume agent</label>
              <input type="text" class="form-control" name="agent_name">
            </div>
            <div class="col-md-6">
              <label for="basic-url">IP-ul agentului</label>
              <div class="input-group">
                <span class="input-group-addon" id="basic-addon3">0.0.0.0</span>
                <input type="text" class="form-control" name="agent_ip">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
        <button type="button" class="btn btn-primary" onclick="submit_form('#saveAgent', '#saveAgentResult', false, function (data) {
          dataResult = JSON.parse(data);
          if (dataResult.description != undefined) {
            $('#requestAlert').html(dataResult.description);
            $('.alert').fadeIn();
          }
        }, {});">Salveaza</button>
      </div>
    </div>
  </div>
</div>

<script>

  $('.alert').hide();

</script>

<?php $this->load->view('layout/footer'); ?>