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
    <div class="card">
      <div class="card-content">
        <button type="button" id="add-agent" class="btn btn-primary btn-lg">Add Agent</button>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">OSSEC Agents</h4>
      </div>
      <div class="card-content">
        <div class="blockLoader agentsTableLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
        <table class="table table-striped dataTable">
          <thead>
            <tr class="active">
              <th width="25">ID</th>
              <th width="100">Agent ID</th>
              <th width="150">Name</th>
              <th width="200">IP</th>
              <th width="580">Date created</th>
              <th width="150">Config file</th>
              <th width="440" class="all">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr class="active">
              <th width="25">ID</th>
              <th width="100">Agent ID</th>
              <th width="150">Name</th>
              <th width="200">IP</th>
              <th width="580">Date created</th>
              <th width="100">Config file</th>
              <th width="440" class="all">Action</th>
            </tr> 
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add agent modal -->
<div class="modal fade" id="addAgent" tabindex="-1" role="dialog" aria-labelledby="addAgent">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create a new agent</h4>
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
              <label for="basic-url">Agent name</label>
              <input type="text" class="form-control" name="agent_name">
            </div>
            <div class="col-md-6">
              <label for="basic-url">Agent IP</label>
              <div class="input-group">
                <span class="input-group-addon" id="basic-addon3">0.0.0.0</span>
                <input type="text" class="form-control" name="agent_ip">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="submit_form('#saveAgent', '#saveAgentResult', false, function (data) {
          dataResult = JSON.parse(data);
          if (dataResult.description != undefined) {
            $('#requestAlert').html(dataResult.description);
            $('.alert').fadeIn();
            // Reload table
            agentsTable.api().ajax.reload();
          }
        }, {});">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- General modal -->
<div class="modal fade" id="generalModal" tabindex="-1" role="dialog" aria-labelledby="generalModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-body">
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Inchide</button>
    </div>
    </div>
  </div>
</div>

<!-- Create config file for agent modal -->
<div class="modal fade" id="agentConfigFile" tabindex="-1" role="dialog" aria-labelledby="agentConfigFile">
  <div class="modal-dialog" role="document">
    
  </div>
</div>

<script>

  $('.alert').hide();

</script>

<?php $this->load->view('layout/footer'); ?>