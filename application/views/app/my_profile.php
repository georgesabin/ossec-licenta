<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'my_profile',
    'menuActive'        => 'dashboard',
    'requireCSS'        => ['pages/my_profile.css'],
    'requireJs'         => ['pages/my_profile.js'],
    'pageTitle'         => 'My profile'
  ]
); ?>
<div class="row">
  <div id="updateMyProfile"></div>
  <form class="" method="POST" action="<?= $this->config->item('base_url');?>app/updateAction" id="updateProfile">
    <div id="updateProfileLoader" class="blockLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
    <input type="hidden" class="formToken" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="user_avatar_default" value="0">
    <div class="row">
      <div class="col-lg-4 col-md-5">
        <div class="card card-user">
          <div class="image">
            <img src="<?= base_url(); ?>assets/img/background.jpg" alt="">
          </div>
          <div class="card-content">
            <div class="author">
              <div class="upload-container">
                <img class="avatar border-white" src="<?= (isset($dates) && isset($dates->user_avatar) && $dates->user_avatar != 'assets/img/faces/face-1.jpg') ? 'files/app/' . $dates->user_avatar : base_url() . 'assets/img/faces/face-1.jpg'; ?>" alt="No Profile Image">
                <div class="default-avatar">
                  <a class="default-upload">
                    <span class="fa fa-camera default-hover fa-2x"></span>
                    <input type="file" id="upload-default-logo" name="user_avatar" value="" placeholder="Choose a file" accept="image/png, image/jpg, image/jpeg">
                  </a>
                </div>
                <div class="changed-avatar">
                  <a class="default-upload change-avatar">
                    <span class="fa fa-camera default-hover fa-2x"></span>
                    <input type="file" id="upload-default-change-logo" name="user_avatar" value="" placeholder="Choose a file" accept="image/png, image/jpg, image/jpeg">
                  </a>
                  <a class="default-upload remove-avatar">
                    <span class="fa fa-times default-hover fa-2x"></span>
                  </a>
                </div>
              </div>
              <h4 class="card-title">
                <?= (isset($dates)) ? $dates->user_full_name : ''; ?> <small>( <a href="mailto:<?= (isset($dates)) ? $dates->user_mail : ''; ?>"><?= (isset($dates)) ? $dates->user_name : ''; ?></a> )</small>
                <br>
                <small><?= (isset($dates)) ? $dates->user_created_date : ''; ?></small>
                <br />
              </h4>
              <hr>
              <div class="text-center">
                <div class="row">
                  <div class="col-md-6">
                    <h5><?= (isset($actionsUser) && isset($actionsUser->{'user.login'})) ? $actionsUser->{'user.login'} : '0'; ?><br><small>Logins</small></h5>
                  </div>
                  <div class="col-md-6">
                    <h5><?= (isset($actionsUser) && isset($actionsUser->{'user.insert'})) ? $actionsUser->{'user.insert'} : '0'; ?><br><small>Users created</small></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-7">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Edit Profile</h4>
          </div>
          <div class="card-content">
            <form>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Email address</label>
                    <input type="email" name="user_mail" class="form-control" placeholder="Email" value="<?= (isset($dates) && isset($dates->user_mail)) ? $dates->user_mail : ''; ?>" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required>
                    <span class="label_error">&nbsp;</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="required">Full Name</label>
                    <input type="text" name="user_full_name" class="form-control" placeholder="Full Name" value="<?= (isset($dates) && isset($dates->user_full_name)) ? $dates->user_full_name : ''; ?>" pattern="[A-Za-z]+" required>
                    <span class="label_error">&nbsp;</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>New password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="*****" value="">
                    <span class="label_error">&nbsp;</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Repeat new password</label>
                    <input type="password" name="repeat_password" class="form-control" placeholder="*****" value="">
                    <span class="label_error">&nbsp;</span>
                  </div>
                </div>
              </div>
            </form>
            <button type="button" class="btn btn-success btn-wd pull-right" onclick="settingPasswordVerification('#updateProfile', 'modal_container_3');">Update profile</button>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<?php $this->load->view('layout/footer'); ?>
