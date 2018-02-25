<?php
$this->load->view(
  'layout/header',
  array(
    'assetsNamespace' 	=> 'login',
    'pageTitle' 		=> 'Login',
    'requireJs' 		=> array('pages/login.js'),
    'requireCSS' 		=> array('pages/login.css'),
    'disableSidebar'    => true
  )
); ?>

<div class="row">
  <div class="col-sm-offset-4 col-sm-4 col-xl-offset-5 col-xl-2">
    <div class="card">
      <div class="blockLoader" id="loginFormLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
      <div class="card-header">
        <?php if(!is_null(get_cookie('userId'))) { ?>
          <div class="row">
            <div class="col-xs-12">
              <div style="text-align: center;">
                <div class="author">
                  <img class="avatar" src="<?= get_cookie('userAvatar') != '' ? base_url().'files/app/'.get_cookie('userAvatar') : '../../assets/img/faces/face-1.jpg'; ?>" alt="..." style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid #eb5e28; margin-bottom: 10px;">
                </div>
                <h4 class="card-title">Welcome back<br /><strong class="text-danger"><?= get_cookie('userFullName'); ?></strong></h4>
              </div>
            </div>
          </div>
        <?php }else{ ?>
          <h4 class="card-title">Login</h4>
          <p class="category">Login using your username and password</p>
        <?php } ?>
      </div>
      <div class="card-content">
        <div class="row">
          <div class="col-xs-12">
            <form id="loginForm" method="POST" action="app/loginAction">
              <input type="hidden" class="formToken" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div id="form_results"></div>
              <div class="login-group">
                <div class="form-group" <?= !is_null(get_cookie('userId')) ? 'style="display: none; margin-bottom: 0; "' : 'style="margin-bottom: 0;"'; ?>>
                  <label for="user_name not_required">Username</label>
                  <input type="text" class="form-control border-input" id="user_name" name="user_name" placeholder="" value="<?= !is_null(get_cookie('userId')) ? get_cookie('userName') : ''; ?>">
                  <span class="label_error">&nbsp;</span>
                </div>
                <div class="form-group">
                  <label for="user_password not_required">Password</label>
                  <input type="password" class="form-control border-input" id="user_password" name="user_password" placeholder="">
                  <span class="label_error">&nbsp;</span>
                </div>
              </div>
              <button type="button" class="btn btn-danger btn-fill btn-wd btn-block" onclick="submit_form('#loginForm', '#form_results')">Login</button>
            </form>
            <?= !is_null(get_cookie('userId')) ? '<p class="category text-center"><small><br />You are not '.get_cookie('userFullName').' ? Click <a href="/login?force">here</a> to login with another account.</small></p>' : ''; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('layout/footer'); ?>
