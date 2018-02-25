<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'audit',
    'menuActive'        => 'audit/index',
    'requireJs'         => ['libs/select2.full.min.js', 'libs/datedropper.min.js', 'pages/audit.js'],
    'requireCSS'        => ['libs/select2.min.css', 'libs/date_filter.css', 'libs/datedropper.min.css', 'pages/audit.css'],
    'pageTitle'         => 'Audit'
  ]
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-content table-filter">
        <div class="row">
          <div class="col-md-2">
            <label for="date_from_audit">Date From</label>
            <input id="date_from_audit" name="date_from_audit" class="form-control" type="text" data-theme="date_filter" data-large-mode="true" data-translate-mode="false" data-auto-lang="true" data-format="Y-m-d" placeholder="Select date from"/>
          </div>
          <div class="visible-xs">&nbsp;</div>
          <div class="col-md-2">
            <label for="date_to_audit">Date To</label>
            <input id="date_to_audit" name="date_to_audit" class="form-control" type="text" data-theme="date_filter" data-large-mode="true" data-translate-mode="false" data-auto-lang="true" data-format="Y-m-d" placeholder="Select date to"/>
          </div>
          <div class="visible-xs">&nbsp;</div>
          <div class="col-md-2">
            <label for="usersFullNameSelector">User</label>
            <select name="usersFullNameSelector" id="usersFullNameSelector" class="form-control">
              <option value=""></option>
              <?php foreach($usersFullNames as $fullName) { ?>
                <option value="<?= $fullName->user_full_name; ?>"><?= $fullName->user_full_name; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="visible-xs">&nbsp;</div>
          <div class="col-md-2">
            <label for="logActionSelector">Log action</label>
            <select name="logActionSelector" id="logActionSelector" class="form-control">
              <option value=""></option>
              <option value="did.update">did.update</option>
              <option value="mass.update">mass.update</option>
              <option value="profile.update">profile.update</option>
              <option value="rule.inbound.delete">rule.inbound.delete</option>
              <option value="rule.inbound.insert">rule.inbound.insert</option>
              <option value="rule.inbound.update">rule.inbound.update</option>
              <option value="rule.outbound.delete">rule.outbound.delete</option>
              <option value="rule.outbound.insert">rule.outbound.insert</option>
              <option value="rule.outbound.update">rule.outbound.update</option>
              <option value="setting.update">setting.update</option>
              <option value="user.delete">user.delete</option>
              <option value="user.insert">user.insert</option>
              <option value="user.login">user.login</option>
              <option value="user.logout">user.logout</option>
              <option value="user.update">user.update</option>
            </select>
          </div>
          <div class="visible-xs">&nbsp;</div>
          <div class="col-md-2">
            <label for="logTargetEntitySelector">Entity type</label>
            <select name="logTargetEntitySelector" id="logTargetEntitySelector" class="form-control">
              <option value=""></option>
              <option value="user">user</option>
              <option value="did">did</option>
              <option value="setting">setting</option>
              <option value="rule_outbound">rule_outbound</option>
              <option value="rule_inbound">rule_inbound</option>
            </select>
          </div>
          <div class="col-md-2"><button class="btn btn-fill btn-block btn-danger btn-getAudit btn-filterData">Filter data</button></div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="blockLoader auditTableLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
      <div class="card-header">
        <h4 class="card-title">Audit table</h4>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
        <table class="table table-striped dataTable">
          <thead>
            <tr class="active">
              <th width="30">ID</th>
              <th>Action ( date )</th>
              <th width="120">User</th>
              <th><span title="Target entity" style="cursor: help;">T.E.</span> type</th>
              <th><span title="Target entity" style="cursor: help;">T.E.</span> id</th>
              <th>Value</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr class="active">
              <th width="30">ID</th>
              <th>Action ( date )</th>
              <th width="120">User</th>
              <th><span title="Target entity" style="cursor: help;">T.E.</span> type</th>
              <th><span title="Target entity" style="cursor: help;">T.E.</span> id</th>
              <th>Value</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('layout/footer'); ?>
