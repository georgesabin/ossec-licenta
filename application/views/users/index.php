<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'users',
    'menuActive'        => 'users/index',
    'pageTitle'         => 'Users',
    'requireJs'         => ['libs/bootstrap-switch.min.js', 'libs/select2.full.min.js', 'libs/datedropper.min.js', 'pages/users.js'],
    'requireCSS'        => ['libs/bootstrap-switch.min.css', 'libs/select2.min.css', 'libs/date_filter.css', 'libs/datedropper.min.css']
  ]
); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-content table-filter">
        <div class="row">
          <div class="col-md-3">
            <label for="user_full_name" class="not_required">Full name</label>
            <input type="text" class="form-control" name="user_full_name" value="" placeholder="Type full name">
          </div>
          <div class="col-md-3">
            <label for="user_name" class="not_required">User name</label>
            <input type="text" class="form-control" name="user_name" value="" placeholder="Type user name">
          </div>
          <div class="col-md-2">
            <label for="user_role" class="not_required">Role</label>
            <select class="form-control" name="user_role">
              <option value=""></option>
              <option value="admin">ADMIN</option>
              <option value="manager">MANAGER</option>
            </select>
          </div>
          <div class="col-md-2">
            <label for="user_mail" class="not_required">Mail</label>
            <input type="text" class="form-control" name="user_mail" value="" placeholder="Type user mail">
          </div>
          <div class="col-md-2">
            <button class="btn btn-fill btn-wd btn-block btn-danger btn-getUsers btn-filterData">Filter data</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Application Users</h4>
      </div>
      <div class="card-content">
        <div class="blockLoader usersTableLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
        <table class="table table-striped dataTable">
          <thead>
            <tr class="active">
              <th>ID</th>
              <th>Full name</th>
              <th>User</th>
              <th width="120">Role</th>
              <th>User mail</th>
              <th width="130">Date created</th>
              <th width="70">Status</th>
              <th width="70" class="all"><button class="btn btn-success btn-fill btn-block btn-xs" onclick="js_modal('modal_container', 'users/user/0');"><i class="fa fa-plus"></i> Add</button></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr class="active">
              <th>ID</th>
              <th>Full name</th>
              <th>User</th>
              <th width="120">Role</th>
              <th>User mail</th>
              <th width="130">Date created</th>
              <th width="70">Status</th>
              <th width="70" class="all"><button class="btn btn-success btn-fill btn-block btn-xs" onclick="js_modal('modal_container', 'users/user/0');"><i class="fa fa-plus"></i> Add</button></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
