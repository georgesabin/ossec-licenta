<?php
$this->load->view(
  'layout/header',
  [
    'assetsNamespace'   => 'settings',
    'menuActive'        => 'settings/index',
    'pageTitle'         => 'Settings',
    'requireJs'         => ['libs/bootstrap-switch.min.js', 'pages/settings.js'],
    'requireCSS'        => ['libs/bootstrap-switch.min.css', 'pages/settings.css']
  ]
); ?>
<div id="saveGeneral"></div>
<div class="row saveGeneral">
  <div class="col-md-12">
    <div class="card">
      <div id="saveGeneralSettingsLoader" class="blockLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
      <div class="card-header">
        <h4 class="card-title">General</h4>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
        <form method="POST" action="<?= $this->config->item('base_url');?>settings/saveGeneralSettings" enctype="multipart/form-data" id="saveGeneralSettingsAction">
          <input type="hidden" class="formToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">Logo</h5>
              <small>Upload a image ( png/jpg ) and set it as the logo of the application. If the image is not uploaded or is removed the default logo will be used.</small>
            </div>
            <div class="col-md-4 col-lg-3 col-xl-2">
              <img src="<?= (isset($settings) && !empty($settings['application_logo']['setting_value']) && $settings['application_logo']['setting_code'] == 'application_logo') ? $this->config->item('base_url') . 'files/app/' . $settings['application_logo']['setting_value'] : $this->config->item('base_url') . 'assets/img/logo.png'; ?>" class="img-responsive img-logo" width=160 height=50>
              <div class="row">
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <a class="btn file-btn btn-primary btn-fill">
                    <span>Upload a new image</span>
                    <input type="file" id="upload-logo" name="application_logo" value="" placeholder="Choose a file" accept="image/png, image/jpg, image/jpeg">
                  </a>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <a class="btn file-btn btn-danger btn-fill pull-right">
                    <span>Remove</span>
                    <button type="button" class="remove-logo-button"></button>
                  </a>
                </div>
                <input type="hidden" class="logo-removed" name="logo_removed">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">Base URL</h5>
              <small>This is used as base url for the application. You can switch ON <strong>"URL Auto"</strong> and this will be automatically set by the application everytime it needs it or type in the exact address of the application.</small>
            </div>
            <div class="col-md-1 col-lg-1 col-xl-1">
              <label>Auto</label>
              <input type="checkbox" class="form-control url-auto-switch" name="application_url_auto" <?= (isset($settings) && isset($settings['application_url_auto']['setting_code']) && $settings['application_url_auto']['setting_code'] == 'application_url_auto' && $settings['application_url_auto']['setting_value'] == 'on') ? 'checked' : ''; ?> data-size="sm" data-off-color="danger" data-on-color="success" data-on-text="ON" data-off-text="OFF"/>
              <span class="label_error">&nbsp;</span>
            </div>
            <div class="col-md-6 col-lg-8 col-xl-9">
              <label>URL</label>
              <input id="url-settings" type="text" class="form-control url-settings <?= isset($settings) && isset($settings['application_url_auto']['setting_code']) && $settings['application_url_auto']['setting_value'] == 'on' ? 'disabled' : ''; ?>" name="application_url" placeholder="http://..." value="<?= (isset($settings) && isset($settings['application_url']['setting_code']) && $settings['application_url']['setting_code'] == 'application_url') ? $settings['application_url']['setting_value'] : '';?>">
              <span class="label_error">&nbsp;</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">Application title</h5>
              <small>This is the title that will appear on the browser's tab ( <i>ex: TITLE &raquo; PAGE NAME</i> ).</small>
            </div>
            <div class="col-md-8 col-lg-9 col-xl-10">
              <label>Title</label>
              <input type="text" class="form-control title-settings" name="application_title" placeholder="Insert title" value="<?= (isset($settings) && isset($settings['application_title']['setting_code']) && $settings['application_title']['setting_code'] == 'application_title') ? $settings['application_title']['setting_value'] : ''; ?>">
              <span class="label_error">&nbsp;</span>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-xs-12">
            <button type="button" class="btn btn-success pull-right" onclick="submit_form('#saveGeneralSettingsAction', '#saveGeneral', 'confirmation', false, {});"><i class="fa fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="saveDeveloper"></div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Developer</h4>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
        <form method="POST" action="<?= $this->config->item('base_url');?>settings/saveDeveloperSettings" id="saveDeveloperSettingsAction">
          <input type="hidden" class="formToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div id="saveDeveloperSettingsLoader" class="blockLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">Sentry</h5>
              <small>DSN for sentry's library ( <i>sentry is used to log errors JS / PHP</i> ). This is recommended to be ON allowing the errors to be sent to the developing team. </small>
            </div>
            <div class="col-md-1 col-lg-1 col-xl-1">
              <label>Status</label><br>
              <input type="checkbox" class="form-control status-switch" name="application_sentry_status" <?= (isset($settings) && isset($settings['application_sentry_status']['setting_code']) && $settings['application_sentry_status']['setting_code'] == 'application_sentry_status' && $settings['application_sentry_status']['setting_value'] == 'on') ? 'checked' : ''; ?> data-size="sm" data-off-color="danger" data-on-color="success" data-on-text="ON" data-off-text="OFF" >
              <span class="label_error">&nbsp;</span>
            </div>
            <div class="col-md-7 col-lg-8 col-xl-9 no-padding">
              <div class="row">
                <div class="col-xs-6">
                  <label>DSN</label>
                  <input type="text" class="form-control dsn-setting <?= isset($settings) && isset($settings['application_sentry_status']['setting_code']) && $settings['application_sentry_status']['setting_value'] != 'on' ? 'disabled' : ''; ?>" name="application_sentry_dsn" placeholder="https://..." value="<?= (isset($settings) && isset($settings['application_sentry_dsn']['setting_value']) && $settings['application_sentry_dsn']['setting_code'] == 'application_sentry_dsn') ? $settings['application_sentry_dsn']['setting_value'] : ''; ?>">
                  <span class="label_error">&nbsp;</span>
                </div>
                <div class="col-xs-6">
                    <label>DSN (Public)</label>
                    <input type="text" class="form-control dsn-public-setting <?= isset($settings) && isset($settings['application_sentry_status']['setting_code']) && $settings['application_sentry_status']['setting_value'] != 'on' ? 'disabled' : ''; ?>" name="application_sentry_dsn_public" placeholder="https://..." value="<?= (isset($settings) && isset($settings['application_sentry_dsn_public']['setting_value']) && $settings['application_sentry_dsn_public']['setting_code'] == 'application_sentry_dsn_public') ? $settings['application_sentry_dsn_public']['setting_value'] : ''; ?>">
                    <span class="label_error">&nbsp;</span>
                </div>
              </div>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-xs-12">
            <button type="button" class="btn btn-success pull-right" onclick="submit_form('#saveDeveloperSettingsAction', '#saveGeneral', 'confirmation', false, {});"><i class="fa fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- <div id="saveAsterisk"></div>
<div class="row">
  <div class="col-md-12">
    <div class="card" id="cardAsterisk">
      <div class="card-header">
        <h4 class="card-title">Asterisk</h4>
        <small>This action will by applied on the asterisk server, syntax errors or wrong logic will might cause problems with the calls.</small>
      </div>
      <div class="clearfix"></div>
      <div class="card-content">
        <div id="saveAsteriskSettingsOutput">

        </div>
        <form method="POST" action="<?= $this->config->item('base_url');?>settings/saveAsteriskSettings" id="saveAsteriskSettingsAction">
          <input type="hidden" class="formToken" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div id="saveAsteriskSettingsLoader" class="blockLoader"><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">sip.conf</h5>
              <small>This is the sip.conf ( <i>usually in /etc/asterisk/sip.conf</i> ) file. </small>
            </div>
            <div class="col-md-8 col-lg-9 col-xl-10">
              <label>SIP</label>
              <textarea class="form-control" id="sip" rows="10" name="asterisk_conf_sip"><?= (isset($settings) && isset($settings['asterisk_conf_sip']['setting_code']) && $settings['asterisk_conf_sip']['setting_code'] == 'asterisk_conf_sip') ? $settings['asterisk_conf_sip']['setting_value'] : ''; ?></textarea>
              <span class="label_error">&nbsp;</span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-2">
              <h5 class="title">extensions.conf</h5>
              <small>This is the extensions.conf ( <i>usually in /etc/asterisk/extensions.conf</i> ) file. </small>
            </div>
            <div class="col-md-8 col-lg-9 col-xl-10">
              <label>Extensions </label>
              <textarea class="form-control" id="extensions" rows="10" name="asterisk_conf_extensions"><?= (isset($settings) && isset($settings['asterisk_conf_extensions']['setting_code']) && $settings['asterisk_conf_extensions']['setting_code'] == 'asterisk_conf_extensions') ? $settings['asterisk_conf_extensions']['setting_value'] : ''; ?></textarea>
              <span class="label_error">&nbsp;</span>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-xs-12">
            <button type="button" class="btn btn-danger pull-right" onclick="settingPasswordVerification('#saveAsteriskSettingsAction', 'modal_container_3', {});"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->

<?php $this->load->view('layout/footer'); ?>
